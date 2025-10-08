<?php 
// ============================================================
// index.php
// ------------------------------------------------------------
// This is the homepage for the FUSS web app.
// It checks whether a user is logged in (via session) and
// displays navigation links accordingly.
// ============================================================

// Start a new session or resume an existing one
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to FUSS</title>
</head>
<body>
    <h1>Flinders Uni Skill Share (FUSS)</h1>

    <?php if (isset($_SESSION["user_id"])): ?>
        <!-- If the user is logged in, show links to dashboard and logout -->
        <p>Welcome back! <a href="dashboard.php">Go to Dashboard</a></p>
        <p><a href="logout.php">Logout</a></p>
    <?php else: ?>
        <!-- If not logged in, show links to register or login -->
        <p><a href="register.php">Register</a> | <a href="login.php">Login</a></p>
    <?php endif; ?>
</body>
</html>
