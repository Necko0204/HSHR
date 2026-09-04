<?php

$applicationTimezone = getenv('APP_TIMEZONE') ?: 'Asia/Manila';
date_default_timezone_set($applicationTimezone);

function createDatabaseConnection(): mysqli
{
    $host = getenv('DB_HOST') ?: 'localhost';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD');
    $database = getenv('DB_NAME') ?: 'humanresource';

    if ($password === false) {
        $password = '';
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $connection = new mysqli($host, $username, $password, $database);
    } catch (mysqli_sql_exception $error) {
        error_log('Database connection failed: ' . $error->getMessage());
        throw new RuntimeException('Database service is unavailable.');
    }

    $connection->set_charset('utf8mb4');
    // MariaDB 11 defaults to uca1400 while the legacy schema uses general_ci.
    // Keep expressions, string literals, CASTs, and stored columns comparable.
    $connection->query("SET collation_connection = 'utf8mb4_general_ci'");

    $databaseTimezone = getenv('DB_TIMEZONE') ?: '+08:00';
    if (!preg_match('/^[+-](?:0\d|1[0-4]):[0-5]\d$/', $databaseTimezone)) {
        $databaseTimezone = '+08:00';
    }
    try {
        $timezoneStatement = $connection->prepare('SET time_zone = ?');
        $timezoneStatement->bind_param('s', $databaseTimezone);
        $timezoneStatement->execute();
        $timezoneStatement->close();
    } catch (mysqli_sql_exception $error) {
        $connection->close();
        error_log('Database timezone configuration failed: ' . $error->getMessage());
        throw new RuntimeException('Database service is unavailable.');
    }

    return $connection;
}
