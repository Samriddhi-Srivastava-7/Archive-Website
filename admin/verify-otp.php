<?php

require_once "../includes/desktop-check.php";

session_start();

$error = "";

if (
    !isset($_SESSION["pending_admin_email"]) ||
    !isset($_SESSION["admin_otp"])
) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $entered_otp = trim($_POST["otp"]);

    $stored_otp = $_SESSION["admin_otp"];

    $otp_created_at = $_SESSION["otp_created_at"];

    $otp_valid_time = 300;

    /* =========================
       OTP EXPIRED
    ========================= */

    if (time() - $otp_created_at > $otp_valid_time) {

        $error = "OTP expired. Please login again.";

        unset($_SESSION["admin_otp"]);
        unset($_SESSION["pending_admin_email"]);
        unset($_SESSION["pending_admin_username"]);
        unset($_SESSION["otp_created_at"]);

    }

    /* =========================
       OTP VERIFIED
    ========================= */

    elseif ($entered_otp == $stored_otp) {

        $_SESSION["admin_logged_in"] = true;

        $_SESSION["admin_email"] =
            $_SESSION["pending_admin_email"];

        $_SESSION["admin_username"] =
            $_SESSION["pending_admin_username"];

        unset($_SESSION["admin_otp"]);
        unset($_SESSION["pending_admin_email"]);
        unset($_SESSION["pending_admin_username"]);
        unset($_SESSION["otp_created_at"]);

        header("Location: dashboard.php");
        exit();

    }

    /* =========================
       INVALID OTP
    ========================= */

    else {

        $error = "Invalid OTP.";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Verify OTP | Certificate Archive</title>

    <link rel="stylesheet" href="../assets/css/admin.css">

</head>

<body class="auth-body">

    <div class="auth-wrapper">

        <div class="auth-card">

            <div class="auth-logo">
                CA
            </div>

            <h1>Verify OTP</h1>

            <p class="auth-subtitle">
                Enter the 6-digit OTP sent to your registered admin email.
            </p>

            <?php if (!empty($error)): ?>

                <div class="auth-error">
                    <?php echo $error; ?>
                </div>

            <?php endif; ?>

            <form method="POST" class="auth-form">

                <label>OTP Code</label>

                <input
                    type="text"
                    name="otp"
                    maxlength="6"
                    placeholder="Enter 6-digit OTP"
                    required
                >

                <button type="submit">
                    Verify OTP
                </button>

            </form>

            <p class="auth-note">
                OTP expires in 5 minutes.
            </p>

        </div>

    </div>

</body>

</html>