<?php
// Start a new or resume the existing session
session_start();

// Include database connection (PDO instance defined in db.php)
require_once("db.php");

// Redirect user to login page if they are not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Retrieve the currently logged in user's ID from the session
$user_id = $_SESSION["user_id"];

// Fetch user information (name, bio, profile picture and credits) from the database
$stmt = $pdo->prepare("SELECT name, bio, profile_picture, fuss_credits FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - FUSS</title>
    <style>
        /* ---------- Page Styling (fancy shit) ---------- */
        body {
            font-family: Arial, sans-serif;
            background: #f9fafb;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        .profile-info {
            flex-grow: 1;
        }
        .profile-info h2 {
            margin: 0;
            color: #333;
        }
        .profile-info p {
            color: #555;
            margin: 5px 0 0;
        }
        .credits-box {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #eef3f7;
            border-radius: 8px;
        }
        .credits {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: white;
        }
        .earn { background: #28a745; } /* Green for earning credits */
        .spend { background: #dc3545; } /* Red for spending credits */
        .earn:hover { background: #218838; }
        .spend:hover { background: #c82333; }
        a {
            display: inline-block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">

    <!-- ===== PROFILE CARD ===== -->
    <div class="profile">
        <!-- Show user’s profile picture or fallback to a default (default doesent work)-->
        <img src="<?= htmlspecialchars($user['profile_picture'] ?? 'default.png') ?>" alt="Profile Picture">

        <div class="profile-info">
            <!-- Display the user’s name and bio -->
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <p><?= htmlspecialchars($user['bio'] ?: "No bio set yet.") ?></p>

            <!-- Link to edit profile page -->
            <a href="profile.php">Edit Profile</a>
        </div>
    </div>

    <!-- ===== CREDIT DISPLAY ===== -->
    <div class="credits-box">
        <p>Your Credits:</p>
        <p class="credits"><?= htmlspecialchars($user['fuss_credits']) ?></p>
    </div>

    <!-- ===== CREDIT ACTIONS ===== -->
    <form method="POST" action="update_credits.php" style="text-align:center;">
        <!-- Earn and Spend buttons trigger different actions handled by update_credits.php (this is just as proof of concept)-->
        <button class="earn" type="submit" name="action" value="earn">Earn 10 Credits</button>
        <button class="spend" type="submit" name="action" value="spend">Spend 10 Credits</button>
    </form>

    <!-- ===== LOGOUT LINK ===== -->
    <p style="text-align:center;">
        <a href="logout.php">Logout</a>
    </p>
</div>
</body>
</html>
