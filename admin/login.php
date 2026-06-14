<?php

require_once "../includes/desktop-check.php";
require_once "../config/mail.php";

session_start();

$error = "";

$admin_username = "admin";
$admin_password = "admin123";
$admin_email = "ichchhit235@gmail.com";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username === $admin_username && $password === $admin_password) {

        $otp = rand(100000, 999999);

        $_SESSION["pending_admin_username"] = $username;
        $_SESSION["pending_admin_email"] = $admin_email;
        $_SESSION["admin_otp"] = $otp;
        $_SESSION["otp_created_at"] = time();

        if (sendOTP($admin_email, $otp)) {
            header("Location: verify-otp.php");
            exit();
        } else {
            $error = "OTP email could not be sent. Please check mail configuration.";
        }

    } else {
        $error = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Certificate Archive</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="auth-body">

<div class="auth-wrapper">

    <div class="auth-card">

    

        <h1>Admin Login</h1>
        <p class="auth-subtitle">Enter credentials</p>

        <?php if (!empty($error)): ?>
            <div class="auth-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">

            <label>Username</label>
            <input type="text" name="username" placeholder="Enter username" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <button type="submit">Continue to OTP</button>

        </form>

        <p class="auth-note">Admin access only</p>

    </div>

</div>

</body>
</html>