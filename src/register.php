<?php
// ============================================================
// register.php
// ------------------------------------------------------------
// This script handles new user registration for FUSS.
// It validates form input, checks if an email already exists,
// hashes the password and creates a new user record in MySQL.
// ============================================================

// Start a new or existing session (optional for feedback use)
session_start();

// Include database connection ($pdo)
require_once "db.php";

// Message variable to show success or error feedback
$message = "";

// ------------------------------------------------------------
// Checks if the form has been submitted via POST
// ------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get input values and sanitize whitespace
    $email = trim($_POST["email"]);
    $name = trim($_POST["name"]);
    $password = $_POST["password"]; // password is hashed later

    // --------------------------------------------------------
    // Basic validation — make sure all fields are filled
    // --------------------------------------------------------
    if (empty($email) || empty($name) || empty($password)) {
        // If any field is blank, set an error message
        $message = "All fields are required.";
    } else {
        try {
            // ------------------------------------------------
            // Check if the email already exists in database
            // ------------------------------------------------
            $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $checkStmt->execute([$email]);

            if ($checkStmt->fetch()) {
                // Email already registered — reject registration
                $message = "This email is already registered.";
            } else {
                // ------------------------------------------------
                // Create a new user account
                // ------------------------------------------------

                // Hash the user's password before storing
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user into the database
                $stmt = $pdo->prepare("
                    INSERT INTO users (email, password_hash, name, fuss_credits)
                    VALUES (?, ?, ?, 0)
                ");
                $stmt->execute([$email, $passwordHash, $name]);

                // Show success message with a link to login
                $message = "Registration successful! <a href='login.php'>Login here</a>.";
            }

        } catch (PDOException $e) {
            // If anything goes wrong, display a safe error message
            // This is a life saver
            $message = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - FUSS</title>
</head>
<body>
    <h1>Register</h1>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>
    <p><?php echo htmlspecialchars($message); ?></p>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>