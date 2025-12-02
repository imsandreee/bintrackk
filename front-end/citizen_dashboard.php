<?php
session_start();
if(!isset($_SESSION['access_token']) || $_SESSION['role'] !== 'citizen'){
    header("Location: index.php");
    exit();
}
?>
<h2>Citizen Dashboard</h2>
<p>Welcome, <?php echo $_SESSION['user_email']; ?>!</p>
<a href="logout.php">Logout</a>
