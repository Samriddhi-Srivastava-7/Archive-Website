<?php

require_once __DIR__ . "/env.php";

$host = $_ENV["DB_HOST"] ?? "";
$user = $_ENV["DB_USER"] ?? "";
$password = $_ENV["DB_PASS"] ?? "";
$database = $_ENV["DB_NAME"] ?? "";

$conn = mysqli_connect($host, $user, $password, $database, 3307);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>