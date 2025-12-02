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
<div class="card issue-tracking-section">
        <h3>📝 My Submitted Reports</h3>
        <p class="section-subtitle">Track the status of issues you've reported to BinTrack.</p>

        <table class="reports-table">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Issue Type</th>
                    <th>Location Details</th>
                    <th>Date Submitted</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="Report ID"><a href="report-details.html?id=10045" class="report-link">#10045</a></td>
                    <td data-label="Issue Type">Overflowing Bin</td>
                    <td data-label="Location Details">Corner of 5th Ave & Pine St</td>
                    <td data-label="Date Submitted">2025-11-28</td>
                    <td data-label="Status" class="status resolved">Resolved</td>
                    <td data-label="Action">
                        <div class="action-dropdown">
                            <button class="action-btn">Actions <i class="fas fa-caret-down"></i></button>
                            <div class="dropdown-content">
                                <a href="report_edit.php" class="edit-action"><i class="fas fa-edit"></i> Edit</a>
                                <a href="#" class="delete-action" data-id="10045"><i class="fas fa-trash-alt"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td data-label="Report ID"><a href="report-details.html?id=10087" class="report-link">#10087</a></td>
                    <td data-label="Issue Type">Illegal Dumping</td>
                    <td data-label="Location Details">Behind City Hall, near lot B</td>
                    <td data-label="Date Submitted">2025-12-01</td>
                    <td data-label="Status" class="status in-review">In Review</td>
                    <td data-label="Action">
                        <div class="action-dropdown">
                            <button class="action-btn">Actions <i class="fas fa-caret-down"></i></button>
                            <div class="dropdown-content">
                                <a href="report_edit.php" class="edit-action"><i class="fas fa-edit"></i> Edit</a>
                                <a href="#" class="delete-action" data-id="10087"><i class="fas fa-trash-alt"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td data-label="Report ID"><a href="report-details.html?id=10112" class="report-link">#10112</a></td>
                    <td data-label="Issue Type">Damaged Bin</td>
                    <td data-label="Location Details">14th St, outside building #C</td>
                    <td data-label="Date Submitted">2025-12-03</td>
                    <td data-label="Status" class="status scheduled">Scheduled</td>
                    <td data-label="Action">
                        <div class="action-dropdown">
                            <button class="action-btn">Actions <i class="fas fa-caret-down"></i></button>
                            <div class="dropdown-content">
                                <a href="report_edit.php" class="edit-action"><i class="fas fa-edit"></i> Edit</a>
                                <a href="#" class="delete-action" data-id="10112"><i class="fas fa-trash-alt"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="pagination">
            <a href="#">&laquo; Previous</a>
            <span>Page 1 of 5</span>
            <a href="#">Next &raquo;</a>
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
/* --- Issue Tracking Section Styles --- */
.issue-tracking-section {
    padding: 25px;
}

.issue-tracking-section h3 {
    color: #1a5e2f; /* Dark Green */
    margin-top: 0;
}

.section-subtitle {
    margin-top: -10px;
    margin-bottom: 20px;
    color: #666;
    font-size: 0.9em;
}

/* --- Report Table Styling --- */
.reports-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.reports-table thead th {
    background-color: #f0f4f7;
    text-align: left;
    padding: 12px;
    border-bottom: 2px solid #ddd;
    font-size: 0.9em;
    color: #333;
}

.reports-table tbody td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 0.9em;
    vertical-align: middle;
}

.reports-table tbody tr:hover {
    background-color: #f9f9f9;
}

/* --- Status Badges --- */
.status {
    font-weight: bold;
    text-align: center;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8em;
    display: inline-block;
}

.status.resolved {
    background-color: #e8f5e9; /* Light Green */
    color: #2e7d32; /* Darker Green */
}

.status.in-review {
    background-color: #fffde7; /* Light Yellow */
    color: #fbc02d; /* Darker Yellow */
}

.status.scheduled {
    background-color: #e1f5fe; /* Light Blue */
    color: #0277bd; /* Darker Blue */
}

/* --- Action Button --- */
.btn-detail {
    padding: 5px 10px;
    border: 1px solid #ccc;
    background-color: #fff;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-detail:hover {
    background-color: #e0e0e0;
}

/* --- Pagination --- */
.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9em;
    color: #666;
}

.pagination a {
    color: #1a5e2f;
    text-decoration: none;
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.pagination a:hover {
    background-color: #e0e0e0;
}
/* --- Action Dropdown Styles --- */
.action-dropdown {
    position: relative; /* Crucial for positioning the dropdown content */
    display: inline-block;
}

.action-btn {
    padding: 8px 12px;
    background-color: #1a5e2f; /* Dark Green */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9em;
    display: flex;
    align-items: center;
    gap: 5px;
}

.action-btn:hover {
    background-color: #144f24;
}

.action-btn i.fa-caret-down {
    margin-left: 5px;
}

.dropdown-content {
    /* Initially Hidden */
    display: none; 
    position: absolute;
    right: 0;
    z-index: 10;
    min-width: 140px;
    background-color: #fff;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    border-radius: 4px;
    overflow: hidden;
}

.dropdown-content a {
    color: #333;
    padding: 10px 14px;
    text-decoration: none;
    display: block;
    font-size: 0.9em;
    transition: background-color 0.1s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.dropdown-content a.delete-action {
    color: #dc3545; /* Red color for Delete action */
}

/* Class to show the dropdown (will be added by JavaScript) */
.action-dropdown.active .dropdown-content {
    display: block;
}
/* Responsive adjustment for table on small screens */
@media (max-width: 768px) {
    .reports-table, .reports-table thead, .reports-table tbody, .reports-table th, .reports-table td, .reports-table tr {
        display: block;
    }

    .reports-table thead {
        /* Hide the header visually, but keep it accessible */
        position: absolute;
        top: -9999px;
        left: -9999px;
    }

    .reports-table tr {
        border: 1px solid #ccc;
        margin-bottom: 10px;
        display: block;
    }

    .reports-table td {
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-left: 50%; /* Space for the label */
        text-align: right;
    }

    /* Create virtual header labels for small screens */
    .reports-table td:before {
        content: attr(data-label);
        position: absolute;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        text-align: left;
        font-weight: bold;
        color: #1a5e2f;
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
<script src="reports-script.js"></script>
</body>
</html>