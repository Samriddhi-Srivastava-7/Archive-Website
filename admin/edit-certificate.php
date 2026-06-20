<?php

require_once "../includes/auth-check.php";
require_once "../config/db.php";
require_once "../includes/csrf.php";

$message = "";

if (!isset($_GET["id"])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET["id"];

$selectSql = "SELECT * FROM certificates WHERE id = '$id' LIMIT 1";
$selectQuery = mysqli_query($conn, $selectSql);

if (mysqli_num_rows($selectQuery) == 0) {
    echo "Certificate not found.";
    exit();
}

$certificate = mysqli_fetch_assoc($selectQuery);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !isset($_POST["csrf_token"]) ||
        !verifyCSRFToken($_POST["csrf_token"])
    ) {
        die("Invalid CSRF Token");
    }

    $student_name = $_POST["student_name"];
    $certificate_type = $_POST["certificate_type"];
    $course = $_POST["course"];
    $issue_date = $_POST["issue_date"];
    $status = $_POST["status"];

    $updateSql = "UPDATE certificates SET
        student_name = '$student_name',
        certificate_type = '$certificate_type',
        course = '$course',
        issue_date = '$issue_date',
        status = '$status'
        WHERE id = '$id'";

    if (mysqli_query($conn, $updateSql)) {
        header("Location: dashboard.php?updated=success");
        exit();
    } else {
        $message = "Update failed: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Certificate</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="auth-body">

    <div class="auth-wrapper">

        <div class="auth-card">

            <div class="auth-logo">CA</div>

            <h1>Edit Certificate</h1>
            <p class="auth-subtitle">Update certificate details and status.</p>

            <?php if (!empty($message)): ?>
                <div class="auth-error"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?php echo generateCSRFToken(); ?>"
                >

                <label>Certificate ID</label>
                <input type="text" value="<?php echo $certificate["certificate_uid"]; ?>" disabled>

                <label>Student Name</label>
                <input type="text" name="student_name" value="<?php echo $certificate["student_name"]; ?>" required>

                <label>Certificate Type</label>
                <input type="text" name="certificate_type" value="<?php echo $certificate["certificate_type"]; ?>" required>

                <label>Course</label>
                <input type="text" name="course" value="<?php echo $certificate["course"]; ?>" required>

                <label>Issue Date</label>
                <input type="date" name="issue_date" value="<?php echo $certificate["issue_date"]; ?>" required>

                <label>Status</label>
                <select name="status" required>
                    <option value="Draft" <?php if ($certificate["status"] == "Draft") echo "selected"; ?>>Draft</option>
                    <option value="Published" <?php if ($certificate["status"] == "Published") echo "selected"; ?>>Published</option>
                    <option value="Revoked" <?php if ($certificate["status"] == "Revoked") echo "selected"; ?>>Revoked</option>
                </select>

                <br><br>

                <button type="submit">Update Certificate</button>

            </form>

            <p class="auth-note">
                <a href="dashboard.php">Back to Dashboard</a>
            </p>

        </div>

    </div>

</div>

</body>
</html>