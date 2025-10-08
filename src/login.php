<?php
// ============================================================
// login.php
// ------------------------------------------------------------
// Handles user login by verifying credentials against the
// database. If successful, a session is created and the user
// is redirected to the dashboard.
// ============================================================

// Start a new session or resume an existing one (yet again)
session_start();

// Include the database connection file (creates $pdo)
require_once "db.php";

// A variable to store any login error message
$message = "";

// ------------------------------------------------------------
// Step 1: Handle form submission (when user clicks "Login")
// ------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve submitted email and password from POST data (remember GET is evil)
    $email = $_POST["email"];
    $password = $_POST["password"];

    // ------------------------------------------------------------
    // Step 2: Fetch the user from the database using a prepared statement
    // ------------------------------------------------------------
    // The query selects the user's ID, password hash and name
    // where the email matches the one entered in the form.
    $stmt = $pdo->prepare("SELECT id, password_hash, name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ------------------------------------------------------------
    // Step 3: Verify password and start session
    // ------------------------------------------------------------
    // password_verify() safely compares the entered password
    // with the hashed password stored in the database.
    if ($user && password_verify($password, $user["password_hash"])) {
        // If valid, store user info in the session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];

        // Redirect to the dashboard and stop the script
        header("Location: dashboard.php");
        exit;
    } else {
        // If login fails, set an error message
        $message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - FUSS</title>
</head>
<body>
    <h1>Login</h1>

    <!-- ------------------------------------------------------------
         Step 4: Login Form
         ------------------------------------------------------------
         The form posts back to this same page when submitted
         (It collects the user's email and password).
    -->
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <!-- Display an error message (if any) -->
    <p><?php echo htmlspecialchars($message); ?></p>

    <!-- Link to go back to the homepage -->
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
