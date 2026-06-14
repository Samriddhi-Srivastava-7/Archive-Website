<?php

require_once "../includes/auth-check.php";
require_once "../config/db.php";

if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $sql = "UPDATE certificates SET status = 'Revoked' WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?revoked=success");
        exit();
    } else {
        echo "Revoke failed: " . mysqli_error($conn);
    }

} else {
    header("Location: dashboard.php");
    exit();
}

?>