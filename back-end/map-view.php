<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BinTrack: IoT Waste Monitoring Map</title>
<!-- Tailwind CSS CDN for styling the UI elements -->
<script src="https://cdn.tailwindcss.com"></script>
<style>
    /*
     * Custom CSS for the map element and marker colors.
     */
    #map {
        /* Ensure map uses 100% of its container (which is now flex-grow h-full) */
        height: 100%; 
        width: 100%;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Styling for custom markers based on bin status */
    .bin-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        line-height: 1;
        border: 3px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .bin-icon:hover {
        transform: scale(1.1);
    }

    /* Status Colors */
    .status-low { background-color: #10B981; /* Emerald Green */ }
    .status-medium { background-color: #FBBF24; /* Amber Yellow */ }
    .status-high { background-color: #EF4444; /* Red */ }
    .status-error { background-color: #6B7280; /* Gray/Sensor Error */ }

    /* Style for the sidebar control panel */
    #control-panel {
        /* Position absolute relative to the new flex-grow parent div */
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 1000; /* Ensure it's above the map */
        max-width: 300px;
    }

    /* Import Inter font for better typography */
    html {
        font-family: 'Inter', sans-serif;
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<!-- Restructure body to use horizontal flex for sidebar and main content -->
<body class="bg-gray-100 min-h-screen flex">

    <?php include '../front-end/components/navbar.php';?>



    <!-- 2. Main Content Area (Map + Floating Panel) -->
    <div class="flex-grow relative p-4 h-screen" id="main-content-wrapper">
        <div id="map"></div>

        <!-- Floating Control Panel -->
        <div id="control-panel" class="bg-white p-4 rounded-lg shadow-xl space-y-3">
            <h2 class="text-lg font-bold text-gray-800">BinTrack Dashboard</h2>
            <p class="text-sm text-gray-600">Real-time Bin Status</p>

            <div id="status-summary" class="grid grid-cols-2 gap-2 text-sm">
                <!-- Summary stats will be populated here -->
            </div>

            <button onclick="zoomToHighPriorityBins()"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                Focus on Urgent Bins
            </button>
        </div>
    </div>


<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Supabase JS CDN (Required for Supabase Integration) -->
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>

<script type="module">
    import { createClient } from "https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm";

    // Supabase credentials (replace with your own)
    const SUPABASE_URL = 'https://imwqlozdfvugwqwdcrag.supabase.co';
    const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imltd3Fsb3pkZnZ1Z3dxd2RjcmFnIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjQ0MDMxNzYsImV4cCI6MjA3OTk3OTE3Nn0.HSNerKsc1-QX09vvyGnImtRJaKMNXL3sNCvIPM9kf7E';
    const BINS_TABLE = 'bins';

    const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

    let map;
    let markers = L.layerGroup();
    const HIGH_PRIORITY_BINS = [];

    function initializeMap() {
        // Default center is near Quezon City, Philippines (14.65° N, 121.04° E)
        map = L.map('map').setView([14.65, 121.04], 14);
        
        // Add the OpenStreetMap tile layer (the base map)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom:19, attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' }).addTo(map);

        // Add the markers layer group to the map
        markers.addTo(map);

        // Make the update function globally accessible 
        window.updateMapMarkers = updateMapMarkers;
        window.zoomToHighPriorityBins = zoomToHighPriorityBins;
        window.reportCollection = reportCollection; // Expose to global scope for button click

        // Fetch initial data and start real-time subscription
        fetchBinData();
        subscribeRealtime();
    }

    /**
     * Fetches the current list of bins from the Supabase database.
     */
    async function fetchBinData() {
        try {
            // Fetch all columns from the bins table
            const { data, error } = await supabase.from(BINS_TABLE).select('*');
            if(error) throw error;
            
            console.log("Fetched live bin data:", data);
            updateMapMarkers(data);
        } catch(err) {
            console.error('Error fetching bins:', err.message);
            // Fallback to mock data if real data fails
            updateMapMarkers(getInitialMockBins());
        }
    }

    /**
     * Creates a mock dataset for initial map render when the database isn't connected.
     * The structure now matches the public.bins schema (gps_lat, gps_lng, fill_percent, etc.).
     */
    function getInitialMockBins() {
        return [
            // Note: The 'status' column is used to control the marker color (low, medium, high, error).
            { id: 1, device_id: 101, location_name: "QC Circle Entrance", gps_lat: 14.655, gps_lng: 121.035, status: 'high', fill_percent: 95, latest_weight_kg: 58.5, latest_ultrasonic_cm: 5.0 },
            { id: 2, device_id: 102, location_name: "Ayala Mall Vertis North", gps_lat: 14.650, gps_lng: 121.042, status: 'low', fill_percent: 40, latest_weight_kg: 22.1, latest_ultrasonic_cm: 30.2 },
            { id: 3, device_id: 103, location_name: "UP TechnoHub Gate", gps_lat: 14.660, gps_lng: 121.050, status: 'medium', fill_percent: 78, latest_weight_kg: 45.0, latest_ultrasonic_cm: 12.5 },
            { id: 4, device_id: 104, location_name: "Centris Walk Area", gps_lat: 14.645, gps_lng: 121.030, status: 'low', fill_percent: 2, latest_weight_kg: 1.2, latest_ultrasonic_cm: 55.8 },
            { id: 5, device_id: 105, location_name: "Cubao Bus Terminal", gps_lat: 14.665, gps_lng: 121.040, status: 'error', fill_percent: 100, latest_weight_kg: 65.0, latest_ultrasonic_cm: 0.0, note: 'Sensor Malfunction/Full' },
        ];
    }

    /**
     * Updates the markers on the map based on the latest bin data.
     * @param {Array<Object>} bins - Array of bin objects from Supabase or mock data.
     */
    function updateMapMarkers(bins) {
        markers.clearLayers(); // Remove all existing markers
        HIGH_PRIORITY_BINS.length = 0; // Clear the high priority list

        let summary = { low: 0, medium: 0, high: 0, error: 0, total: bins.length };

        bins.forEach(bin => {
            // Use new schema column names for coordinates
            if(typeof bin.gps_lat !== 'number' || typeof bin.gps_lng !== 'number') {
                console.warn(`Skipping bin ${bin.location_name}: Invalid coordinates.`);
                return;
            }

            let statusClass = `status-${bin.status ? bin.status.toLowerCase() : 'low'}`;
            
            // Use new schema column name for fill level
            let fill = bin.fill_percent || 0; 
            let fillText = fill > 99 ? '!' : `${Math.round(fill)}%`;

            // Custom DivIcon for the marker
            let binIcon = L.divIcon({ 
                className: `bin-icon ${statusClass}`, 
                html: fillText, 
                iconSize:[30, 30], 
                iconAnchor:[15, 15] 
            });

            // Create the marker using new schema column names
            let marker = L.marker([bin.gps_lat, bin.gps_lng], { icon: binIcon });

            // Format numbers for display
            const weight = (bin.latest_weight_kg || 0).toFixed(1);
            const ultrasonic = (bin.latest_ultrasonic_cm || 0).toFixed(1);

            // Create the popup content with new data fields
            const popupContent = `
                <div class="p-2">
                    <h3 class="text-base font-bold">${bin.location_name} (ID: ${bin.id})</h3>
                    <p class="text-sm">Device ID: <span class="font-semibold">${bin.device_id}</span></p>
                    <p class="text-sm">Status: <span class="font-semibold">${(bin.status || 'LOW').toUpperCase()}</span></p>
                    <hr class="my-1"/>
                    <p class="text-sm">Fill Level: <span class="font-medium">${Math.round(fill)}%</span></p>
                    <p class="text-sm">Weight: <span class="font-medium">${weight} kg</span></p>
                    <p class="text-sm">Distance to Waste: <span class="font-medium">${ultrasonic} cm</span></p>
                    
                    ${bin.note ? `<p class="text-xs text-red-500 mt-1">Note: ${bin.note}</p>` : ''}
                    
                    <button class="mt-2 text-xs bg-blue-500 text-white p-1 rounded hover:bg-blue-600"
                            onclick="reportCollection('${bin.id}', '${bin.location_name}')">
                        Report Collection
                    </button>
                </div>
            `;

            marker.bindPopup(popupContent, { maxWidth: 200 });
            markers.addLayer(marker);

            // Update summary stats (status field is fine as-is)
            const currentStatus = (bin.status || 'low').toLowerCase();
            if(currentStatus in summary) {
                summary[currentStatus]++;
            } else {
                summary.error++; // Treat unknown status as error/urgent
            }
            
            // Track high priority bins for routing/focus
            if(currentStatus === 'high' || currentStatus === 'error') {
                HIGH_PRIORITY_BINS.push(marker);
            }
        });

        updateControlPanel(summary);
    }

    /**
     * Updates the status summary in the floating control panel.
     * @param {Object} summary - The counts for each status.
     */
    function updateControlPanel(summary) {
        const urgent = (summary.high || 0) + (summary.error || 0);
        const panel = document.getElementById('status-summary');
        
        // Using flex-col and items-center for centered, stacked alignment
        panel.innerHTML = `
            <div class="p-2 rounded-lg bg-gray-50 border border-gray-200 flex flex-col items-center justify-center">
                <p class="font-extrabold text-xl text-gray-700">${summary.total}</p>
                <p class="text-gray-500 text-xs mt-1">Total Bins</p>
            </div>
            <div class="p-2 rounded-lg bg-green-50 border border-green-200 flex flex-col items-center justify-center">
                <p class="font-extrabold text-xl text-green-600">${summary.low || 0}</p>
                <p class="text-gray-500 text-xs mt-1">Low</p>
            </div>
            <div class="p-2 rounded-lg bg-yellow-50 border border-yellow-200 flex flex-col items-center justify-center">
                <p class="font-extrabold text-xl text-yellow-600">${summary.medium || 0}</p>
                <p class="text-gray-500 text-xs mt-1">Medium</p>
            </div>
            <div class="p-2 rounded-lg bg-red-50 border border-red-200 flex flex-col items-center justify-center">
                <p class="font-extrabold text-xl text-red-600">${urgent}</p>
                <p class="text-gray-500 text-xs mt-1">Urgent / Error</p>
            </div>
        `;
    }

    /**
     * Adjusts the map view to encompass all high-priority bins.
     */
    function zoomToHighPriorityBins() {
        if(HIGH_PRIORITY_BINS.length===0) {
            // Use a custom modal instead of alert()
            showCustomModal('No Urgent Bins', 'There are currently no bins requiring urgent attention (High or Error status).', 'bg-green-500');
            return;
        }

        // Create a LatLngBounds object and extend it with all high-priority bin locations
        const bounds = L.featureGroup(HIGH_PRIORITY_BINS).getBounds();

        // Fit the map view to the calculated bounds with some padding
        map.fitBounds(bounds, { 
            padding:[50,50], 
            maxZoom:16 // Prevent zooming in too close if markers are clustered
        });

        // Use a custom modal instead of alert()
        showCustomModal('Focusing View', `Zoomed to ${HIGH_PRIORITY_BINS.length} urgent bin locations.`, 'bg-blue-500');
    }

    /**
     * Mock function for reporting collection (Placeholder for API call).
     * @param {string} binId - The ID of the bin to be reported/reset.
     * @param {string} binName - The human-readable name of the bin.
     */
    function reportCollection(binId, binName) {
        // In a live Supabase app, you would use:
        // supabase.from('bins').update({ fill_percent: 0, status: 'low', updated_at: new Date() }).eq('id', binId)
        
        showCustomModal('Collection Reported', `Simulated action: Request to collect waste from ${binName} (ID: ${binId}) has been recorded! This would reset the bin's fill level and status in Supabase.`, 'bg-yellow-500');
    }

    // Subscribe to Supabase realtime updates
    function subscribeRealtime() {
        supabase
            .channel('bin-updates')
            .on('postgres_changes', { event:'*', schema:'public', table:BINS_TABLE }, payload => {
                console.log('Realtime update:', payload);
                fetchBinData();
            })
            .subscribe();
    }


    // --- Custom Modal Implementation (Replacing alert/confirm) ---
    function showCustomModal(title, message, colorClass = 'bg-blue-500') {
        let modal = document.getElementById('custom-modal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'custom-modal';
            modal.className = 'fixed inset-0 z-[2000] flex items-start justify-center p-6 transition-opacity duration-300 opacity-0 pointer-events-none';
            modal.innerHTML = `
                <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
                <div class="relative ${colorClass} text-white p-4 rounded-lg shadow-2xl mt-20 max-w-sm w-full transform -translate-y-10 transition-transform duration-300">
                    <div class="flex justify-between items-center">
                        <h4 id="modal-title" class="font-bold text-lg"></h4>
                        <button id="close-modal" class="text-white opacity-70 hover:opacity-100">&times;</button>
                    </div>
                    <p id="modal-message" class="mt-2 text-sm"></p>
                </div>
            `;
            document.body.appendChild(modal);

            document.getElementById('close-modal').onclick = () => {
                modal.classList.remove('opacity-100', 'pointer-events-auto');
                modal.querySelector('.relative').classList.remove('translate-y-0');
            };
        }

        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-message').textContent = message;
        const content = modal.querySelector('.relative');
        content.className = `relative ${colorClass} text-white p-4 rounded-lg shadow-2xl mt-20 max-w-sm w-full transform -translate-y-10 transition-transform duration-300`;

        // Display and animate
        setTimeout(() => {
            modal.classList.add('opacity-100', 'pointer-events-auto');
            content.classList.add('translate-y-0');
        }, 10);

        // Auto-close after 4 seconds
        setTimeout(() => {
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            content.classList.remove('translate-y-0');
        }, 4000);
    }
    // --- End Custom Modal Implementation ---


    window.onload = initializeMap;
</script>
</body>
</html>