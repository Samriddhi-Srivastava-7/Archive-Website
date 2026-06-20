<?php

require_once "../includes/desktop-check.php";
require_once "../includes/csrf.php";
require_once "../includes/validation.php";

$error = "";

if (
    !isset($_SESSION["pending_admin_email"]) ||
    !isset($_SESSION["admin_otp"])
) {
    header("Location: login.php");
    exit();
}

/* =========================
   OTP ATTEMPT INITIALIZATION
========================= */

if (!isset($_SESSION["otp_attempts"])) {
    $_SESSION["otp_attempts"] = 0;
}

/* =========================
   MAX OTP ATTEMPT CHECK
========================= */

if ($_SESSION["otp_attempts"] >= 5) {

    session_unset();
    session_destroy();

    header("Location: login.php?locked=1");
    exit();
}

/* =========================
   FORM SUBMIT
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !isset($_POST["csrf_token"]) ||
        !verifyCSRFToken($_POST["csrf_token"])
    ) {
        die("Invalid CSRF Token");
    }

    $errors = [];

    validateRequired(
        $_POST["otp"] ?? "",
        "OTP",
        $errors
    );

    validateMaxLength(
        $_POST["otp"] ?? "",
        "OTP",
        6,
        $errors
    );

    if (
        !ctype_digit($_POST["otp"] ?? "") ||
        strlen($_POST["otp"] ?? "") !== 6
    ) {
        $errors[] = "OTP must be a 6-digit number.";
    }

    if (!empty($errors)) {

        $error = implode("<br>", $errors);

    } else {

        $entered_otp = cleanInput($_POST["otp"]);

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
            unset($_SESSION["otp_attempts"]);

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
            unset($_SESSION["otp_attempts"]);

            header("Location: dashboard.php");
            exit();

        }

        /* =========================
           INVALID OTP
        ========================= */

        else {

            $_SESSION["otp_attempts"]++;

            $remainingAttempts =
                5 - $_SESSION["otp_attempts"];

            if ($remainingAttempts <= 0) {

                session_unset();
                session_destroy();

                header("Location: login.php?locked=1");
                exit();

            } else {

                $error =
                    "Invalid OTP. Remaining attempts: "
                    . $remainingAttempts;

            }

        }

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

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?php echo generateCSRFToken(); ?>"
                >

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
                Maximum 5 incorrect attempts are allowed.
            </p>

        </div>

    </div>

</body>

</html>