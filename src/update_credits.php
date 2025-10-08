<?php
// ============================================================
// update_credits.php
// ------------------------------------------------------------
// This script processes credit actions ("earn" or "spend").
// It runs only for logged-in users and modifies the user's
// fuss_credits balance in the database safely via PDO.
// ============================================================

// Start session to identify which user is logged in (yet again)
session_start();

// Include your PDO connection ($pdo)
require_once("db.php");

// ------------------------------------------------------------
// Ensure the user is logged in
// ------------------------------------------------------------
if (!isset($_SESSION["user_id"])) {
    // Redirect to login if not authenticated
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"]; // current logged-in user's ID

// ------------------------------------------------------------
// Handle the form submission
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action']; // either 'earn' or 'spend'

    // --------------------------------------------------------
    // Handle the "earn" action (for note this is temporary)
    // --------------------------------------------------------
    if ($action === 'earn') {
        // Add 10 credits to the user's total
        $stmt = $pdo->prepare("
            UPDATE users
            SET fuss_credits = fuss_credits + 10
            WHERE id = ?
        ");
        $stmt->execute([$user_id]);

    // --------------------------------------------------------
    // Handle the "spend" action (for note this is temporary)
    // --------------------------------------------------------
    } elseif ($action === 'spend') {
        // First, get the user's current credit balance
        $stmt = $pdo->prepare("
            SELECT fuss_credits
            FROM users
            WHERE id = ?
        ");
        $stmt->execute([$user_id]);
        $credits = $stmt->fetchColumn();

        // If the user has at least 10 credits, spend them
        if ($credits >= 10) {
            $stmt = $pdo->prepare("
                UPDATE users
                SET fuss_credits = fuss_credits - 10
                WHERE id = ?
            ");
            $stmt->execute([$user_id]);
        } else {
            // Otherwise, set a session message (used on dashboard)
            $_SESSION['message'] = "Not enough credits!";
        }
    }

    // --------------------------------------------------------
    // Redirect back to dashboard
    // --------------------------------------------------------
    // This prevents resubmission if the user refreshes the page
    header("Location: dashboard.php");
    exit;
}
?>
