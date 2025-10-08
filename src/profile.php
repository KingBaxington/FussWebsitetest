<?php
// ============================================================
// profile.php
// ------------------------------------------------------------
// Allows logged-in users to view and update their profile,
// including name, degree, college, academic year, bio
// and profile picture. 
// ============================================================

// Start or resume the session so we can access session variables (yet again)
session_start();

// Include the database connection (creates $pdo)
require_once("db.php");

// ------------------------------------------------------------
// Step 1: Redirecting users who are not logged in
// ------------------------------------------------------------
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Send them to login
    exit;                          // Stop script execution
}

// Store the logged-in user's ID from the session
$user_id = $_SESSION["user_id"];
$message = ""; // For showing feedback to the user later

// ------------------------------------------------------------
// Step 2: Handling form submission (when user clicks “Save Changes”)
// ------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize all text inputs to prevent XSS
    $name = htmlspecialchars($_POST["name"]);
    $degree = htmlspecialchars($_POST["degree"]);
    $college = htmlspecialchars($_POST["college"]);
    $academic_year = htmlspecialchars($_POST["academic_year"]);
    $bio = htmlspecialchars($_POST["bio"]);

    // --------------------------------------------------------
    // Optional: Handle profile picture upload (this one was fun stack overflow my beloved)
    // --------------------------------------------------------
    $profile_picture = null;
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/"; // Directory where pictures are stored

        // Create uploads directory if it doesn’t exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Use a unique filename to prevent overwriting
        $filename = basename($_FILES["profile_picture"]["name"]);
        $target_file = $upload_dir . uniqid() . "_" . $filename;

        // Move uploaded file from temp to permanent location
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

        // Store the new image path
        $profile_picture = $target_file;
    }

    // --------------------------------------------------------
    // Update user information in the database
    // --------------------------------------------------------
    $sql = "UPDATE users 
            SET name=?, degree=?, college=?, academic_year=?, bio=?";
    $params = [$name, $degree, $college, $academic_year, $bio];

    // If a new profile picture was uploaded, add it to the query
    if ($profile_picture) {
        $sql .= ", profile_picture=?";
        $params[] = $profile_picture;
    }

    // Add WHERE clause to ensure only current user's row is updated
    $sql .= " WHERE id=?";
    $params[] = $user_id;

    // Execute the prepared statement safely
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Display success message
    $message = "Profile updated successfully!";
}

// ------------------------------------------------------------
// Step 3: Fetch the current user's existing profile data
// ------------------------------------------------------------
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile - FUSS</title>
    <style>
        /* The fancy stuff again */
        body {
            font-family: Arial, sans-serif;
            background: #fafafa;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin: auto;
        }
        input, textarea {
            width: 100%;
            margin: 8px 0;
            padding: 8px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Profile</h2>

    <!-- Feedback message (e.g., “Profile updated successfully”) -->
    <p class="message"><?= $message ?></p>

    <!-- Profile edit form -->
    <form method="POST" enctype="multipart/form-data">
        <label>Full Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label>Degree:</label>
        <input type="text" name="degree" value="<?= htmlspecialchars($user['degree']) ?>">

        <label>College:</label>
        <input type="text" name="college" value="<?= htmlspecialchars($user['college']) ?>">

        <label>Academic Year:</label>
        <input type="text" name="academic_year" value="<?= htmlspecialchars($user['academic_year']) ?>">

        <label>Bio:</label>
        <textarea name="bio"><?= htmlspecialchars($user['bio']) ?></textarea>

        <!-- Profile picture upload -->
        <label>Profile Picture:</label><br>
        <?php if ($user["profile_picture"]): ?>
            <img src="<?= htmlspecialchars($user['profile_picture'] ?: 'uploads/default.png') ?>" class="profile-pic"><br>
        <?php endif; ?>
        <input type="file" name="profile_picture">

        <br><br>
        <button type="submit">Save Changes</button>
    </form>

    <p><a href="dashboard.php">← Back to Dashboard</a></p>
</div>
</body>
</html>
