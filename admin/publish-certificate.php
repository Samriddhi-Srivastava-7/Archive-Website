<?php

require_once "../includes/auth-check.php";
require_once "../config/db.php";

if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $sql = "UPDATE certificates SET status = 'Published' WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?published=success");
        exit();
    } else {
        echo "Publish failed: " . mysqli_error($conn);
    }

} else {
    header("Location: dashboard.php");
    exit();
}

?>