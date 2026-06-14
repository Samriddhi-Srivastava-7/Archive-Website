<?php

$configFile = __DIR__ . "/db.json";

$configData = file_get_contents($configFile);

$dbConfig = json_decode($configData, true);

$host = $dbConfig["host"];
$user = $dbConfig["user"];
$password = $dbConfig["password"];
$database = $dbConfig["database"];

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>