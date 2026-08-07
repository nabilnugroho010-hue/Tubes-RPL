<?php
/**
 * Authentication Check for Admin Pages
 * Include this file at the top of all admin pages
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page
    header("Location: login.php");
    exit;
}
