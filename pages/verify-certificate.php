<?php
include '../includes/header.php';
require_once "../config/db.php";

$result = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $certificate_uid = trim($_POST["certificate_uid"]);

     $sql = "SELECT * FROM certificates WHERE certificate_uid = '$certificate_uid' AND status = 'Published' LIMIT 1";
    $query = mysqli_query($conn, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $result = mysqli_fetch_assoc($query);
    } else {
        $error = "No certificate found with this Certificate ID.";
    }
}
?>

<section class="verify-section">
    <div class="verify-box">

        <div class="verify-badge">Official Verification Portal</div>

        <h1>Verify Certificate</h1>

        <p class="verify-subtitle">
            Enter the unique Certificate ID printed on your certificate.
        </p>

        <form method="POST" class="verify-form">

            <div class="input-group">
                <label>Certificate ID</label>
                <input
                    type="text"
                    name="certificate_uid"
                    placeholder="Example: CERT-DF3BF7EE"
                    required
                >
            </div>

            <button type="submit">Verify Certificate</button>
        </form>

        <p class="verify-note">
            This system verifies certificate records issued by Archive Site.
        </p>

        <?php if ($result): ?>
            <div class="result-box success">
                <div class="result-icon">✓</div>

                <h2>Certificate Verified</h2>

                <p><strong>Certificate ID:</strong> <?php echo htmlspecialchars($result["certificate_uid"]); ?></p>
                <p><strong>Student Name:</strong> <?php echo htmlspecialchars($result["student_name"]); ?></p>
                <p><strong>Certificate Type:</strong> <?php echo htmlspecialchars($result["certificate_type"]); ?></p>
                <p><strong>Course / Role:</strong> <?php echo htmlspecialchars($result["course"]); ?></p>
                <p><strong>Issue Date:</strong> <?php echo htmlspecialchars($result["issue_date"]); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($result["status"]); ?></p>

                <?php if (!empty($result["file_path"])): ?>
                    <a href="../<?php echo htmlspecialchars($result["file_path"]); ?>" target="_blank">
                        View Certificate PDF
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="result-box error">
                <div class="result-icon">!</div>

                <h2>Verification Failed</h2>

                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include '../includes/footer.php'; ?>