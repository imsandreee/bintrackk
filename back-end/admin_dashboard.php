<?php
session_start();

// Only allow admin users
if(!isset($_SESSION['user_email']) || ($_SESSION['role'] ?? '') !== 'admin'){
    header("Location: index.php");
    exit();
}

include 'config.php';

// Helper to fetch Supabase REST API data
function supabase_get($endpoint, $query = "") {
    global $supabase_url, $supabase_key;
    $url = $supabase_url . "/rest/v1/" . $endpoint;
    if ($query) $url .= "?" . $query;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key",
        "Accept: application/json"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Fetch bins from Supabase
$bins = supabase_get("bins", "select=*");
$devices = supabase_get("devices", "select=*");

// Compute summary stats
$total_bins = count($bins);
$full_bins = 0;
$offline_sensors = 0;
$total_weight = 0;

foreach($bins as &$bin){
    // ... existing computations

    // Determine sensor status
   $last_update = $bin['updated_at'] ?? null;

if ($last_update) {
    $last_update_ts = strtotime($last_update); // UTC timestamp from Supabase
$now_utc_ts = gmdate("U");                // ensure UTC now
$threshold_sec = 3600;

if (($now_utc_ts - $last_update_ts) <= $threshold_sec) {
    $sensor_status = "<span class='px-2 py-1 text-xs rounded-lg bg-green-100 text-green-800'>Active</span>";
} else {
    $sensor_status = "<span class='px-2 py-1 text-xs rounded-lg bg-red-100 text-red-800'>Offline</span>";
}

} else {
    $sensor_status = "<span class='px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-700'>No Data</span>";
}


    // Assign to the bin array
    $bin['sensor_status'] = $sensor_status;

    // Convert last_update to PH time for display
    if ($last_update) {
        $last_update_ts = strtotime($last_update);
        $last_update_ph = date("Y-m-d H:i:s", $last_update_ts + 8*3600); // UTC+8
    } else {
        $last_update_ph = '--';
    }
    $bin['last_update_ph'] = $last_update_ph;
}
unset($bin); // break reference




?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BinTrack Admin Dashboard</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        
    </style>
</head>
<body>

    <div class="flex h-screen">
        <!-- Side Navigation Bar -->
        <?php include '../front-end/components/navbar.php';?>


        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
            <header class="mb-6 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
                <div class="text-sm text-gray-500">
                    Last Updated: <span id="last-updated">--:--</span>
                </div>
            </header>

            <!-- Bin Status Overview (Cards) -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Bins Card -->
                <div class="bg-white p-6 rounded-2xl card-shadow border-l-4 border-green-500 hover:shadow-lg transition">
                    <p class="text-sm font-medium text-gray-500 truncate">Total Bins Deployed</p>
                    <div class="mt-1 flex justify-between items-center">
                        <span class="text-3xl font-semibold text-gray-900" id="total-bins"><?php echo $total_bins; ?></span>

                        <div class="p-2 bg-green-100 text-green-600 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Full Bins Card (Requires Collection) -->
                <div class="bg-white p-6 rounded-2xl card-shadow border-l-4 border-red-500 hover:shadow-lg transition">
                    <p class="text-sm font-medium text-gray-500 truncate">Bins Needing Collection</p>
                    <div class="mt-1 flex justify-between items-center">
<span class="text-3xl font-semibold text-red-600" id="full-bins"><?php echo $full_bins; ?></span>
                        <div class="p-2 bg-red-100 text-red-600 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.39 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Offline Sensors Card -->
                <div class="bg-white p-6 rounded-2xl card-shadow border-l-4 border-yellow-500 hover:shadow-lg transition">
                    <p class="text-sm font-medium text-gray-500 truncate">Offline Sensors</p>
                    <div class="mt-1 flex justify-between items-center">
<span class="text-3xl font-semibold text-yellow-600" id="offline-sensors"><?php echo $offline_sensors; ?></span>
                        <div class="p-2 bg-yellow-100 text-yellow-600 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Weight in System -->
                <div class="bg-white p-6 rounded-2xl card-shadow border-l-4 border-emerald-500 hover:shadow-lg transition">
                    <p class="text-sm font-medium text-gray-500 truncate">Total Waste in System</p>
                    <div class="mt-1 flex justify-between items-center">
<span class="text-3xl font-semibold text-emerald-600" id="total-weight"><?php echo number_format($total_weight,2); ?></span>
                        <div class="p-2 bg-emerald-100 text-emerald-600 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 12h12l3-12H3z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6v-1a3 3 0 013-3h6a3 3 0 013 3v1"></path></svg>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Charts and Detailed Table Section -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            

                <!-- Detailed Bin Status Table -->
<div class="lg:col-span-2 bg-white p-6 rounded-2xl card-shadow overflow-x-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Detailed Bin Status Overview</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-xl">Bin ID</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device UID</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fill Level</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight (kg)</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sensor Status</th>
                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-xl">Last Update</th>
            </tr>
        </thead>
        <tbody id="bin-status-table-body" class="bg-white divide-y divide-gray-200">
<?php foreach($bins as $bin):

    // Get device UID (from devices list)
    $device = array_filter($devices, fn($d) => ($d['id'] ?? null) == ($bin['device_id'] ?? null));
    $device_uid = $device ? (array_values($device)[0]['device_uid'] ?? "--") : "--";

    // Latest sensor values on the bin row
    $ultra = isset($bin['latest_ultrasonic_cm']) ? $bin['latest_ultrasonic_cm'] : null;
    $weight = isset($bin['latest_weight_kg']) ? $bin['latest_weight_kg'] : null;
    $fill = isset($bin['fill_percent']) ? $bin['fill_percent'] : null;

    // Per-bin max depth (fallback to 100 cm)
    $max_depth = $bin['max_depth_cm'] ?? 100;

    // Compute fill if not stored
    if ($fill === null && $ultra !== null && $max_depth > 0) {
        $computed_fill = round((1 - ($ultra / $max_depth)) * 100, 1);
        if ($computed_fill < 0) $computed_fill = 0;
        if ($computed_fill > 100) $computed_fill = 100;
        $fill_display = $computed_fill . "%";
    } elseif ($fill !== null) {
        $fill_display = (string)$fill . "%";
    } else {
        $fill_display = "--";
    }

    // Last update: prefer bin.updated_at, fallback to devices.last_online if available
    $last_update = $bin['updated_at'] ?? ($bin['last_online'] ?? null);

    // --- Determine sensor status (Active / Offline / No Data) ---
    // Set threshold here (e.g., '-1 hour' means Active if updated within last hour)
    $threshold = '-1 hour';
    if ($last_update && strtotime($last_update) !== false) {
        $is_online = strtotime($last_update) > strtotime($threshold);
        if ($is_online) {
            $sensor_status = "<span class='px-2 py-1 text-xs rounded-lg bg-green-100 text-green-800'>Active</span>";
        } else {
            $sensor_status = "<span class='px-2 py-1 text-xs rounded-lg bg-red-100 text-red-800'>Offline</span>";
        }
    } else {
        $sensor_status = "<span class='px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-700'>No Data</span>";
    }
?>
<tr>
    <td class="px-3 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($bin['id']); ?></td>

    <td class="px-3 py-2 text-sm text-gray-700">
        <?php echo htmlspecialchars($bin['location_name'] ?? '--'); ?>
    </td>

    <td class="px-3 py-2 text-sm text-gray-700">
        <?php echo htmlspecialchars($device_uid); ?>
    </td>

    <td class="px-3 py-2 text-sm text-gray-700">
        <?php echo $fill_display; ?>
    </td>

    <td class="px-3 py-2 text-sm text-gray-700">
        <?php echo ($weight !== null) ? htmlspecialchars($weight) . " kg" : "--"; ?>
    </td>

    <td class="px-3 py-2">
    <?php echo $bin['sensor_status']; ?>
</td>

<td class="px-3 py-2 text-sm text-gray-700">
    <?php echo $bin['last_update_ph']; ?>
</td>

</tr>
<?php endforeach; ?>
</tbody>

    </table>
</div>


                <!-- Chart 2: Bin Status Distribution -->
                <div class="bg-white p-6 rounded-2xl card-shadow">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Bin Fill Status Distribution (Count)</h2>
                    <div class="h-80 flex items-center justify-center">
                        <canvas id="statusDistributionChart" class="max-h-full"></canvas>
                    </div>
                </div>
            </section>

            <!-- Map View Placeholder / Quick Actions -->
            <section class="mt-6">
                <div class="bg-white p-6 rounded-2xl card-shadow">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions & Map Preview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 bg-gray-100 border border-gray-200 rounded-lg h-64 flex items-center justify-center">
                            <span class="text-gray-500 text-lg">Map Visualization of Bin Locations (Click "Map View" in sidebar)</span>
                        </div>
                        <div class="space-y-4">
                            <button onclick="handleQuickAction('Dispatch Collector')" class="w-full py-3 px-4 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-150 shadow-md">
                                Dispatch Collector
                            </button>
                            <button onclick="handleQuickAction('Generate Priority Report')" class="w-full py-3 px-4 bg-yellow-500 text-white font-semibold rounded-xl hover:bg-yellow-600 transition duration-150 shadow-md">
                                Generate Priority Report
                            </button>
                            <button onclick="handleQuickAction('Acknowledge All Alerts')" class="w-full py-3 px-4 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition duration-150 shadow-md">
                                Acknowledge All Alerts
                            </button>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>

    
<script>
    // Pass PHP data to JS (use only fields present on $bin)
    const binDataList = <?php
        $bin_js = [];
        foreach($bins as $bin){
            $max_depth = $bin['max_depth_cm'] ?? 100;
            $ultra = $bin['latest_ultrasonic_cm'] ?? null;
            if ($ultra !== null && $max_depth > 0) {
                $fill = round((1 - ($ultra / $max_depth)) * 100);
                if ($fill < 0) $fill = 0;
                if ($fill > 100) $fill = 100;
            } else {
                $fill = 0;
            }

            $device_uid = "--";
            foreach ($devices as $d) {
                if (($d['id'] ?? null) == ($bin['device_id'] ?? null)) {
                    $device_uid = $d['device_uid'] ?? "--";
                    break;
                }
            }

            $last = $bin['updated_at'] ?? ($bin['last_online'] ?? null);
            $status = ($last && strtotime($last) > strtotime('-1 hour')) ? 'Online' : 'Offline';

            $bin_js[] = [
                'id' => $bin['id'] ?? null,
                'location' => $bin['location_name'] ?? '',
                'fill' => $fill,
                'weight' => $bin['latest_weight_kg'] ?? 0,
                'status' => $status,
                'lastUpdate' => $last ?? '--',
                'device_uid' => $device_uid
            ];
        }
        echo json_encode($bin_js);
    ?>;

   void updateBinStatus(float weight, float distance, float fill_percent) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("WiFi not connected");
        return;
    }

    HTTPClient http;
    String url = String(bins_url) + "?device_id=eq." + String(device_id);
    http.begin(url);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("apikey", supabase_key);
    http.addHeader("Authorization", String("Bearer ") + supabase_key);
    http.addHeader("Prefer", "return=minimal");

    // --- Get current UTC time ---
    struct tm timeinfo;
    if(!getLocalTime(&timeinfo)){
        Serial.println("Failed to obtain time");
        return;  // don't send update without timestamp
    }
    char buffer[25];
    strftime(buffer, sizeof(buffer), "%Y-%m-%dT%H:%M:%SZ", &timeinfo); // ISO 8601 UTC

    // --- Build JSON payload ---
    StaticJsonDocument<256> doc;
    doc["fill_percent"] = fill_percent;
    doc["latest_weight_kg"] = weight;
    doc["latest_ultrasonic_cm"] = distance;
    doc["updated_at"] = String(buffer);  // include UTC timestamp

    String json;
    serializeJson(doc, json);

    // --- Send PATCH request ---
    int httpResponseCode = http.PATCH(json);
    if (httpResponseCode > 0) {
        Serial.print("Bin status updated (HTTP "); 
        Serial.print(httpResponseCode); 
        Serial.println(")");
    } else {
        Serial.print("Error updating bin status: "); 
        Serial.println(http.errorToString(httpResponseCode));
    }

    http.end();
}
    
    HTTPClient http;

    // Correct Supabase URL with query filter
    String url = String(bins_url) + "?device_id=eq." + String(device_id);
    http.begin(url);

    // Correct headers
    http.addHeader("Content-Type", "application/json");
    http.addHeader("apikey", supabase_key);
    http.addHeader("Authorization", String("Bearer ") + supabase_key);
    http.addHeader("Prefer", "return=minimal");

    // JSON Body
    StaticJsonDocument<256> doc;
    doc["fill_percent"] = fill_percent;
    doc["latest_weight_kg"] = weight;
    doc["latest_ultrasonic_cm"] = distance;

    // Include current timestamp in UTC (ISO 8601 format)
    // Include timestamp in UTC
time_t now;
struct tm timeinfo;
if(!getLocalTime(&timeinfo)){
    Serial.println("Failed to obtain time");
}
char buffer[25];
strftime(buffer, sizeof(buffer), "%Y-%m-%dT%H:%M:%SZ", &timeinfo);
doc["updated_at"] = String(buffer);


    String json;
    serializeJson(doc, json);

    // SEND PATCH REQUEST
    int httpResponseCode = http.PATCH(json);

    if (httpResponseCode > 0) {
      Serial.print("Bin status updated (Response: ");
      Serial.print(httpResponseCode);
      Serial.println(")");
    } else {
      Serial.print("Error updating bin status: ");
      Serial.println(http.errorToString(httpResponseCode));
    }

    http.end();
  } else {
    Serial.println("WiFi not connected for Update");
  }
}

const lastUpdated = binDataList
    .map(bin => bin.lastUpdate)
    .filter(lu => lu !== '--')
    .sort((a, b) => new Date(b) - new Date(a))[0] ?? '--';

// Convert to PH time
if(lastUpdated !== '--'){
    const phTime = new Date(lastUpdated);
    phTime.setHours(phTime.getHours() + 8); // adjust UTC to UTC+8
    document.getElementById('last-updated').textContent = phTime.toLocaleString('en-PH', {
        year: 'numeric', month: '2-digit', day: '2-digit',
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        hour12: false
    });
} else {
    document.getElementById('last-updated').textContent = '--';
}


</script>


   <script src="../front-end/assets/js/script.js"></script>
   <script>
const binDataList = <?php
$bin_js = [];
foreach($bins as $bin){
    $max_depth = $bin['max_depth_cm'] ?? 100;
    $ultra = $bin['latest_ultrasonic_cm'] ?? null;
    if ($ultra !== null && $max_depth > 0) {
        $fill = round((1 - ($ultra / $max_depth)) * 100);
        $fill = max(0, min(100, $fill));
    } else {
        $fill = 0;
    }

    $device_uid = "--";
    foreach ($devices as $d) {
        if (($d['id'] ?? null) == ($bin['device_id'] ?? null)) {
            $device_uid = $d['device_uid'] ?? "--";
            break;
        }
    }

    $last = $bin['updated_at'] ?? ($bin['last_online'] ?? null);
    $status = ($last && strtotime($last) > strtotime('-1 hour')) ? 'Online' : 'Offline';

    $bin_js[] = [
        'id' => $bin['id'] ?? null,
        'location' => $bin['location_name'] ?? '',
        'fill' => $fill,
        'weight' => $bin['latest_weight_kg'] ?? 0,
        'status' => $status,
        'lastUpdate' => $last ?? '--',
        'device_uid' => $device_uid
    ];
}
echo json_encode($bin_js);
?>;

// --- Prepare data for Bin Fill Distribution Chart ---
const fillCounts = {
    "Full (>=90%)": 0,
    "High (75-89%)": 0,
    "Medium (50-74%)": 0,
    "Low (<50%)": 0
};

binDataList.forEach(bin => {
    if (bin.fill >= 90) fillCounts["Full (>=90%)"]++;
    else if (bin.fill >= 75) fillCounts["High (75-89%)"]++;
    else if (bin.fill >= 50) fillCounts["Medium (50-74%)"]++;
    else fillCounts["Low (<50%)"]++;
});

// Chart.js config
const ctx = document.getElementById('statusDistributionChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: Object.keys(fillCounts),
        datasets: [{
            label: 'Number of Bins',
            data: Object.values(fillCounts),
            backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'],
            borderColor: ['#b91c1c', '#d97706', '#2563eb', '#059669'],
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Bins'
                },
                ticks: { precision: 0 }
            },
            y: { grid: { display: false } }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    title: ctx => ctx[0].label,
                    label: ctx => `Bins: ${ctx.formattedValue}`
                }
            }
        }
    }
});
</script>

</body>
</html>