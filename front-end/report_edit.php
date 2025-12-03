<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BinTrack Citizen Report</title>
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

            <div class="card edit-report-card">
                <h2>✏️ Edit Issue Report #10045</h2>
                <p class="section-subtitle">Make changes below and submit to update your report details.</p>

                <form id="editReportForm">
                    
                    <div class="form-group">
                        <label for="reportStatus">Current Status</label>
                        <p class="status resolved" id="reportStatus">Resolved</p>
                        <p class="status-note">Status cannot be changed by the citizen.</p>
                    </div>

                    <div class="form-group">
                        <label for="issueType">Issue Type *</label>
                        <select id="issueType" name="issueType" required>
                            <option value="Overflowing Bin" selected>Overflowing Bin</option>
                            <option value="Damaged Bin">Damaged Bin/Structure</option>
                            <option value="Illegal Dumping">Illegal Dumping</option>
                            <option value="Missed Collection">Missed Collection</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="locationDetails">Location Details *</label>
                        <input type="text" id="locationDetails" name="locationDetails" 
                               value="Corner of 5th Ave & Pine St (Bin A45)" required>
                        <p class="input-hint">Be specific (e.g., street corner, nearest address, bin ID).</p>
                    </div>

                    <div class="form-group">
                        <label for="description">Detailed Description *</label>
                        <textarea id="description" name="description" rows="6" required>
Bin #A45 on the corner of Elm St and 5th Ave is completely full, and trash is now piled up around it, blocking the sidewalk. Need urgent collection.
                        </textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary update-btn">Update Report</button>
                        <a href="citizen_reports.php" class="btn-cancel">Cancel & Back to Reports</a>
                    </div>
                </form>
            </div>

        </main>
        </div>

</body>
</html>

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
.main-content {
    flex: 3; /* Takes up 60% of the space */
    display: flex;
    flex-direction: column;
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
/* --- Edit Report Page Specific Styles --- */

.edit-report-card {
    max-width: 800px; /* Make the card wider for better form layout */
    margin: 40px auto; /* Center the card on the page */
}

.edit-report-card h2 {
    border-bottom: 2px solid #f0f4f7;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #1a5e2f; 
}

/* Reusing and adjusting existing input styles */
#editReportForm input[type="text"],
#editReportForm select,
#editReportForm textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
    box-sizing: border-box; 
    transition: border-color 0.2s;
}

#editReportForm input[type="text"]:focus,
#editReportForm select:focus,
#editReportForm textarea:focus {
    border-color: #ff9800; /* Orange focus glow */
    outline: none;
}

.input-hint {
    font-size: 0.8em;
    color: #888;
    margin-top: 5px;
    font-style: italic;
}

/* Status display styling */
#reportStatus {
    font-size: 1.1em;
    padding: 8px 15px;
    display: inline-block;
    border-radius: 4px;
}

.status-note {
    font-size: 0.85em;
    color: #dc3545; /* Red warning for uneditable status */
    margin-top: 5px;
}

/* Action buttons at the bottom */
.form-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
    align-items: center;
}

.update-btn {
    /* Reuse btn-primary style from the dashboard */
    background-color: #1a5e2f;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.2s;
}

.update-btn:hover {
    background-color: #144f24;
}

.btn-cancel {
    color: #666;
    text-decoration: none;
    padding: 12px 0;
    font-size: 0.9em;
}

.btn-cancel:hover {
    color: #333;
    text-decoration: underline;
}
</Style>
</body>
</html>