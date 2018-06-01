<?php
// Autoload our classes
spl_autoload_register(function ($className) {
    include __DIR__ . '/classes/' . $className . '.class.php';
});

// Load configuration file
require_once 'config.php';

date_default_timezone_set(TIMEZONE);

// Database connection link
$link = null;

/**
 * Connects to database.
 * @throws Exception
 */
function connectToDB(){
    global $link;

    $link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

    // If connection fails
    if ($link == null) {
        throw new Exception('Could not connect to database.');
    }

    mysqli_set_charset($link, "utf8");
}