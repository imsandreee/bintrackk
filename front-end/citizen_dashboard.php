<?php
session_start();
if(!isset($_SESSION['access_token']) || $_SESSION['role'] !== 'citizen'){
    header("Location: index.php");
    exit();
}
?>
<h2>Citizen Dashboard</h2>
<p>Welcome, <?php echo $_SESSION['user_email']; ?>!</p>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BinTrack Citizen Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <header class="navbar">
         <div class="logo-area">
                <!-- Replace this with your actual image -->
                <img src="assets/img/bintrack-logo.png" alt="BinTrack Logo" class="logo-img">

                <div>
                    <h1 class="logo-title">BINTRACK</h1>
                </div>
            </div>
        <nav>
            <a href="citizen_dashboard.php">Dashboard</a>
            <a href="citizen_reports.php">My Reports</a>
            <a href="citizen_guide.php">Guidelines</a>
            <button class="btn-login">
                <a href="logout.php">Log Out</a>
            </button>
        </nav>
    </header>

    <div class="dashboard-container">
        <main class="main-content">
            <div class="map-section">
                <h2>📍 Interactive Live Bin Map</h2>
                <div class="map-placeholder">
                    <p>Map Placeholder - Real-time Bin Locations will appear here.</p>
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
                <p>Total Bins Monitored: **1,450**</p>
                <p>Bins Requiring Attention (🔴): **45 (3.1%)**</p>
                <hr>
                <p>Next Collection Date (Your Area): **Wednesday, Dec 3**</p>
            </div>

            <div class="card report-issue-card">
                <h3>⚠️ Report an Issue</h3>
                <p>Help us keep the city clean by reporting issues quickly.</p>
                <form>
                    <select id="issue-type" required>
                        <option value="">Select Issue Type</option>
                        <option value="overflow">Overflowing Bin</option>
                        <option value="damaged">Damaged Bin/Structure</option>
                        <option value="dumping">Illegal Dumping</option>
                        <option value="missed">Missed Collection</option>
                    </select>
                    <textarea placeholder="Brief description (e.g., location details)" rows="3" required></textarea>
                    <button type="submit" class="btn-primary">Submit Report</button>
                </form>
            </div>
        </aside>
    </div>

<Style>
/* --- Global Styles and Typography --- */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f6f9;
    color: #333;
}

.dashboard-container {
    max-width: 1300px;
    margin: 20px auto;
    padding: 0 20px;
    display: flex;
    gap: 20px;
}
.logo-area {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo-img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.logo-title {
    font-size: 20px;
    font-weight: bold;
    color: #111;
}

/* --- Navigation Bar --- */
.navbar {
    background-color: #ffffff;
    padding: 15px 40px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar a {
    color: #333;
    text-decoration: none;
    padding: 8px 15px;
    margin: 0 5px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.navbar a:hover {
    background-color: #e0e0e0;
}

.btn-login, .btn-primary {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: opacity 0.2s;
}

.btn-login {
    background-color: #1a5e2f; /* Dark Green */
    color: white;
    margin-left: 20px;
}

.btn-login:hover {
    opacity: 0.9;
}

/* --- Main Dashboard Layout --- */
.main-content {
    flex: 3; /* Takes up 60% of the space */
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.sidebar {
    flex: 2; /* Takes up 40% of the space */
}

.card {
    background-color: #ffffff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
}

/* --- Map Section --- */
.map-section {
    background-color: #ffffff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.map-placeholder {
    height: 450px;
    background-color: #e6e9ee; /* Light gray background for map */
    border-radius: 6px;
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-style: italic;
}

.map-legend {
    margin-top: 15px;
    display: flex;
    gap: 25px;
    font-size: 0.9em;
}

.dot {
    height: 12px;
    width: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 5px;
}

.empty { background-color: #4CAF50; /* Green */ }
.half { background-color: #FFC107; /* Yellow/Orange */ }
.full { background-color: #F44336; /* Red */ }

/* --- Report Issue Card --- */
.report-issue-card form select,
.report-issue-card form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.report-issue-card h3 {
    margin-top: 0;
    color: #1a5e2f;
}

.btn-primary {
    background-color: #1a5e2f;
    color: white;
    width: 100%;
}

.btn-primary:hover {
    background-color: #144f24;
}

/* --- Key Metrics --- */
.key-metrics h3 {
    color: #333;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.key-metrics p {
    margin: 8px 0;
}

/* --- Transparency & Impact Section --- */
.transparency-section {
    padding: 25px;
    background-color: #e0f2f1; /* Light teal background */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.transparency-section h3 {
    color: #00796b; /* Darker teal for contrast */
    margin-top: 0;
}

.stats-grid {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    margin-top: 15px;
}

.stat-card {
    background-color: white;
    padding: 15px;
    border-radius: 6px;
    text-align: center;
    flex: 1;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.stat-card i {
    font-size: 1.5em;
    color: #1a5e2f;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 1.5em;
    font-weight: bold;
    color: #1a5e2f;
    margin: 0;
}

.stat-label {
    font-size: 0.8em;
    color: #666;
    margin: 0;
}

/* --- Responsive Adjustments --- */
@media (max-width: 992px) {
    .dashboard-container {
        flex-direction: column; /* Stacks columns vertically on smaller screens */
    }
    .main-content, .sidebar {
        flex: auto;
    }
    .stats-grid {
        flex-direction: column; /* Stacks stats vertically */
    }
    .navbar {
        flex-direction: column;
        gap: 10px;
    }
    .navbar nav {
        text-align: center;
    }
}
</Style>
 <script src="script.js"></script>
</body>
</html>