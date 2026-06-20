<?php

require_once "../includes/auth-check.php";
require_once "../config/db.php";
require_once "../config/storage.php";
require_once "../includes/csrf.php";
require_once "../includes/validation.php";

$message = "";

function getCertificateTypeCode($certificate_type) {

    $type = strtolower(trim($certificate_type));

    if (str_contains($type, "internship")) {
        return "INT";
    }

    if (str_contains($type, "experience")) {
        return "EXP";
    }

    if (str_contains($type, "training")) {
        return "TRN";
    }

    return "CRT";
}

function generateCertificateUID($conn, $certificate_type, $issue_date) {

    $typeCode = getCertificateTypeCode($certificate_type);

    $year = date("Y", strtotime($issue_date));

    $prefix = $typeCode . "-" . $year . "-";

    $sql = "SELECT COUNT(*) AS total
            FROM certificates
            WHERE certificate_uid LIKE '$prefix%'";

    $query = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($query);

    $nextNumber = $row["total"] + 1;

    $sequence = str_pad($nextNumber, 3, "0", STR_PAD_LEFT);

    return $prefix . $sequence;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !isset($_POST["csrf_token"]) ||
        !verifyCSRFToken($_POST["csrf_token"])
    ) {
        die("Invalid CSRF Token");
    }

    $errors = [];

    validateCertificateForm($_POST, $errors);
    validateCertificatePDF($_FILES["certificate_pdf"], $errors);

    if (!empty($errors)) {

        $message = implode("<br>", $errors);

    } else {

        $student_name = cleanInput($_POST["student_name"]);
        $certificate_type = cleanInput($_POST["certificate_type"]);
        $course = cleanInput($_POST["course"]);
        $issue_date = cleanInput($_POST["issue_date"]);
        $status = "Draft";

        $certificate_uid = generateCertificateUID(
            $conn,
            $certificate_type,
            $issue_date
        );

        $file_name = $_FILES["certificate_pdf"]["name"];
        $file_tmp = $_FILES["certificate_pdf"]["tmp_name"];

        $safe_original_name = preg_replace(
            "/[^a-zA-Z0-9._-]/",
            "-",
            basename($file_name)
        );

        $new_file_name = $certificate_uid . "-" . $safe_original_name;

        $uploadedFile = uploadCertificateFile(
            $file_tmp,
            $new_file_name
        );

        if ($uploadedFile) {

            $db_file_path = $uploadedFile["file_path"];

            $sql = "INSERT INTO certificates
            (
                certificate_uid,
                student_name,
                certificate_type,
                course,
                issue_date,
                status,
                file_name,
                file_path
            )
            VALUES
            (
                '$certificate_uid',
                '$student_name',
                '$certificate_type',
                '$course',
                '$issue_date',
                '$status',
                '$new_file_name',
                '$db_file_path'
            )";

            if (mysqli_query($conn, $sql)) {

                $message =
                    "Certificate uploaded successfully. Certificate ID: "
                    . $certificate_uid;

            } else {

                $message = "Database error: " . mysqli_error($conn);

            }

        } else {

            $message = "File upload failed.";

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>Upload Certificate</title>

    <link rel="stylesheet" href="../assets/css/admin.css">

</head>

<body>

<div class="admin-layout">

    <aside class="sidebar" id="sidebar">

        <div class="brand">

            <div class="brand-icon">CA</div>

            <div class="brand-text">
                <h2>Certificate Archive</h2>
                <p>Admin Panel</p>
            </div>

        </div>

        <button class="sidebar-toggle">☰</button>

        <nav class="sidebar-nav">

            <a href="dashboard.php" class="nav-link">
                <span class="nav-icon">▣</span>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="upload-document.php" class="nav-link active">
                <span class="nav-icon">↑</span>
                <span class="nav-text">Upload Certificate</span>
            </a>

            <a href="manage-documents.php" class="nav-link">
                <span class="nav-icon">☷</span>
                <span class="nav-text">Manage Certificates</span>
            </a>

            <div class="nav-section-title">Account</div>

            <a href="logout.php" class="nav-link logout-link">
                <span class="nav-icon">↳</span>
                <span class="nav-text">Logout</span>
            </a>

        </nav>

    </aside>

    <main class="admin-main">

        <section class="dashboard-heading">

            <p>Certificate Management</p>

            <h1>Upload Certificate</h1>

        </section>

        <section class="data-panel">

            <div class="data-panel-header">

                <div>
                    <h2>New Certificate Upload</h2>

                    <p>
                        Upload and save certificate records into the archive system.
                    </p>
                </div>

            </div>

            <div style="padding: 24px;">

                <?php if (!empty($message)): ?>

                    <div class="auth-error">
                        <?php echo $message; ?>
                    </div>

                <?php endif; ?>

                <form
                    method="POST"
                    enctype="multipart/form-data"
                    class="auth-form"
                >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php echo generateCSRFToken(); ?>"
                    >

                    <label>Student Name</label>

                    <input
                        type="text"
                        name="student_name"
                        required
                    >

                    <label>Certificate Type</label>

                    <input
                        type="text"
                        name="certificate_type"
                        placeholder="Internship / Experience / Training"
                        required
                    >

                    <label>Course / Role</label>

                    <input
                        type="text"
                        name="course"
                        required
                    >

                    <label>Issue Date</label>

                    <input
                        type="date"
                        name="issue_date"
                        required
                    >

                    <label>Certificate PDF</label>

                    <input
                        type="file"
                        name="certificate_pdf"
                        accept="application/pdf"
                        required
                    >

                    <button type="submit">
                        Upload Certificate
                    </button>

                </form>

            </div>

        </section>

    </main>

</div>

</body>
</html>