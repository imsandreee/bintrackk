<?php
// 1. PHP: Ensure session is started for access control
session_start();

// 2. PHP: SECURITY FIXES - Use !empty() and strictly check session variables
if (empty($_SESSION['access_token']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'citizen')) {
    header("Location: index.php");
    exit();
}

// 3. PHP: Use a variable for the welcome message to keep the HTML cleaner and use htmlspecialchars for security
$user_email = isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'Citizen';

// 4. IMPORTANT: Replace YOUR_GOOGLE_MAPS_API_KEY with your actual key
// Storing this in a config file or environment variable is best practice, 
// but for demonstration, we'll put a placeholder here.
$maps_api_key = 'YOUR_GOOGLE_MAPS_API_KEY'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BinTrack Citizen Dashboard</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</head>
<body>

    <header class="navbar">
        <div class="logo-area">
            <img src="./assets/img/bintrack-logo.png" alt="BinTrack Logo" class="logo-img">
            <div>
                <h1 class="logo-title">BINTRACK</h1>
            </div>
        </div>
        <nav>
            <a href="dashboard.php" class="nav-item">Dashboard</a>
            <a href="reports.php" class="nav-item">My Reports</a>
            <a href="guidelines.php" class="nav-item">Guidelines</a>
            
            <form action="../back-end/logout.php" method="POST" class="logout-form">
                <button type="submit" class="btn-logout">Log Out</button>
            </form>
        </nav>
    </header>

    
    <h2>Citizen Dashboard</h2>
    <p>Welcome, **<?php echo $user_email; ?>**!</p>
    
    <div class="dashboard-container">
        <main class="main-content">
            <div class="map-section">
                <h2>📍 Interactive Live Bin Map</h2>
<div id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>
                    </div>
                <div class="map-legend">
                    <span><span class="dot empty"></span> Empty</span>
                    <span><span class="dot half"></span> Half Full</span>
                    <span><span class="dot full"></span> Full</span>
                </div>
            </div>

            <div class="transparency-section">
                <h3>📈 Our Municipal Impact</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-trash-can"></i>
                        <p class="stat-value">92%</p>
                        <p class="stat-label">Successful Collections</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-route"></i>
                        <p class="stat-value">25%</p>
                        <p class="stat-label">Reduction in Trips</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-cloud"></i>
                        <p class="stat-value">8.5 T</p>
                        <p class="stat-label">CO2 Emissions Saved</p>
                    </div>
                </div>
            </div>
        </main>

        <aside class="sidebar">
            <div class="card key-metrics">
                <h3>📊 Key Metrics</h3>
                <p>Total Bins Monitored: <strong>1,450</strong></p>
                <p>Bins Requiring Attention (<span style="color:red;">🔴</span>): <strong>45 (3.1%)</strong></p>
                <hr>
                <p>Next Collection Date (Your Area): <strong>Wednesday, Dec 3</strong></p>
            </div>

            <div class="card report-issue-card">
                <h3>⚠️ Report an Issue</h3>
                <p>Help us keep the city clean by reporting issues quickly.</p>
                <form action="report_handler.php" method="POST">
                    <select id="issue-type" name="issue_type" required>
                        <option value="">Select Issue Type</option>
                        <option value="overflow">Overflowing Bin</option>
                        <option value="damaged">Damaged Bin/Structure</option>
                        <option value="dumping">Illegal Dumping</option>
                        <option value="missed">Missed Collection</option>
                    </select>
                    <textarea name="description" placeholder="Brief description (e.g., location details)" rows="3" required></textarea>
                    <button type="submit" class="btn-primary">Submit Report</button>
                </form>
            </div>
        </aside>
    </div>
    
  <script>
    // Init map (example location: Manila)
    var map = L.map('map').setView([14.65, 121.05], 13);

    // Load free OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Example bin marker (Full)
    var fullBin = L.marker([14.655, 121.045]).addTo(map);
    fullBin.bindPopup("<b>Full Bin</b><br>Requires collection.");
</script>

    <footer class="footer">
        <p>© 2025 BinTrack. Smart Waste Management System.</p>
    </footer>

</body>
</html>