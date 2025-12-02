<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BinTrack Citizen Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="script.js"></script>
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
<div class="guidelines-container">
    <div class="guidelines-header">
        <h1>🗑️ Waste Disposal Guidelines</h1>
        <p>Ensure timely collection and a cleaner environment by sorting your waste correctly.</p>
    </div>

    <div class="guidelines-grid">

        <div class="guideline-card non-recyclable">
            <i class="fas fa-trash-can icon-large"></i>
            <h3>General (Non-Recyclable) Waste</h3>
            <p>Place these items in your general waste bin (usually black or green).</p>
            <ul>
                <li>Disposable diapers, sanitary pads, tissues</li>
                <li>Contaminated paper (pizza boxes, coffee cups)</li>
                <li>Used cooking oil (sealed in a container)</li>
                <li>Broken glass and ceramics (securely wrapped)</li>
            </ul>
        </div>

        <div class="guideline-card recyclable">
            <i class="fas fa-recycle icon-large"></i>
            <h3>Recyclables (Clean & Dry)</h3>
            <p>All items must be clean and dry. Use your dedicated recycling bin (usually blue or yellow).</p>
            <ul>
                <li>**Paper & Cardboard:** Magazines, junk mail, clean corrugated boxes (flattened)</li>
                <li>**Plastics:** Bottles (PET), jugs, containers (look for codes 1, 2, 5)</li>
                <li>**Metals:** Aluminum cans, tin cans, aluminum foil</li>
                <li>**Glass:** Bottles and jars (lids removed)</li>
            </ul>
        </div>

        <div class="guideline-card hazardous">
            <i class="fas fa-biohazard icon-large"></i>
            <h3>Hazardous & Special Waste</h3>
            <p>These items CANNOT be placed in standard bins. Check local drop-off centers.</p>
            <ul>
                <li>Batteries (all types)</li>
                <li>Electronics (e-waste like phones, monitors)</li>
                <li>Paint, thinners, and chemical solvents</li>
                <li>Fluorescent light bulbs</li>
            </ul>
        </div>

        <div class="guideline-card tips">
            <i class="fas fa-check-circle icon-large"></i>
            <h3>Collection Day Tips</h3>
            <ol>
                <li>Place bins curbside **before 7:00 AM** on your collection day.</li>
                <li>Ensure the bin lid is fully closed.</li>
                <li>Keep bins at least 3 feet away from parked cars or obstacles.</li>
            </ol>
        </div>
        
    </div>
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
/* --- Guidelines Section Styles --- */

.guidelines-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.guidelines-header {
    text-align: center;
    margin-bottom: 30px;
}

.guidelines-header h1 {
    color: #1a5e2f; /* Dark Green */
    font-size: 2.5em;
    margin-bottom: 5px;
}

.guidelines-header p {
    color: #666;
    font-size: 1.1em;
}

.guidelines-grid {
    display: grid;
    /* Create a responsive grid layout */
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.guideline-card {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    border-left: 5px solid; /* Placeholder for color-coding */
}

.guideline-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.guideline-card h3 {
    margin-top: 10px;
    font-size: 1.5em;
}

.guideline-card ul, .guideline-card ol {
    padding-left: 20px;
    font-size: 0.95em;
}

.guideline-card li {
    margin-bottom: 8px;
    line-height: 1.4;
}

.icon-large {
    font-size: 2.5em;
    margin-bottom: 10px;
    display: block;
}

/* --- Specific Color Coding --- */

/* Recyclable - Green/Blue theme */
.guideline-card.recyclable {
    border-left-color: #28a745;
}
.guideline-card.recyclable h3 {
    color: #28a745;
}
.guideline-card.recyclable .icon-large {
    color: #28a745;
}

/* Non-Recyclable - Standard/Neutral theme */
.guideline-card.non-recyclable {
    border-left-color: #6c757d;
}
.guideline-card.non-recyclable h3 {
    color: #6c757d;
}
.guideline-card.non-recyclable .icon-large {
    color: #6c757d;
}

/* Hazardous - Red/Warning theme */
.guideline-card.hazardous {
    border-left-color: #dc3545;
}
.guideline-card.hazardous h3 {
    color: #dc3545;
}
.guideline-card.hazardous .icon-large {
    color: #dc3545;
}

/* Tips - General Information theme */
.guideline-card.tips {
    border-left-color: #007bff;
}
.guideline-card.tips h3 {
    color: #007bff;
}
.guideline-card.tips .icon-large {
    color: #007bff;
}

/* --- Responsive Adjustments --- */
@media (max-width: 768px) {
    .guidelines-grid {
        /* On smaller screens, stack the cards */
        grid-template-columns: 1fr;
    }
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

</body>
</html>