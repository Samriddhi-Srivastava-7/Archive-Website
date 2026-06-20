<?php

require_once "../includes/auth-check.php";
require_once "../config/db.php";
require_once "../includes/csrf.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit();
}

if (
    !isset($_POST["csrf_token"]) ||
    !verifyCSRFToken($_POST["csrf_token"])
) {
    die("Invalid CSRF Token");
}

if (!isset($_POST["id"])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_POST["id"];

$deleteSql = "DELETE FROM certificates WHERE id = '$id' LIMIT 1";

if (mysqli_query($conn, $deleteSql)) {
    header("Location: dashboard.php?deleted=success");
    exit();
} else {
    echo "Delete failed: " . mysqli_error($conn);
}

?>