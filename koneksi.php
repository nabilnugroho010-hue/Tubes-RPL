<?php
/**
 * Database Connection - Backward Compatibility Wrapper
 * This file provides backward compatibility for existing code
 * Uses the new config/database.php for actual connection
 */

// Include the new database configuration
require_once __DIR__ . '/config/database.php';

// Get connection
$conn = getDatabaseConnection();
