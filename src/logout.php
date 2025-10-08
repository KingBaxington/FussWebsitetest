<?php
// ============================================================
// logout.php
// ------------------------------------------------------------
// This script logs the user out by destroying their session
// and redirecting them back to the homepage (this one is the smallest). 
// ============================================================

// Start or resume the current session
// (needed to access and destroy it)
session_start();

// Destroy all session data (logs the user out)
session_destroy();

// Redirect the user back to the home page
header("Location: index.php");

// Always call exit() after a header redirect to
// prevent the rest of the script from running.
// It's comon practice I think.
exit;
