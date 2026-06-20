<?php

require_once "desktop-check.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================
   LOGIN CHECK
========================= */

if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: ../admin/login.php");
    exit();
}

/* =========================
   SESSION DATA CHECK
========================= */

if (
    !isset($_SESSION["admin_username"]) ||
    !isset($_SESSION["admin_email"])
) {
    session_unset();
    session_destroy();

    header("Location: ../admin/login.php");
    exit();
}
$sessionTimeout = 1800;

if (
    isset($_SESSION["last_activity"]) &&
    (time() - $_SESSION["last_activity"]) > $sessionTimeout
) {
    session_unset();
    session_destroy();

    header("Location: ../admin/login.php?expired=1");
    exit();
}

$_SESSION["last_activity"] = time();

?>