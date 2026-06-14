<?php
require_once "config/db.php";

$result = null;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $certificate_uid = trim($_POST["certificate_uid"]);

    $sql = "SELECT * FROM certificates WHERE certificate_uid = '$certificate_uid' LIMIT 1";
    $query = mysqli_query($conn, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $result = mysqli_fetch_assoc($query);
    } else {
        $message = "No certificate found with this Certificate ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Certificate</title>
</head>
<body>

<h1>Verify Certificate</h1>

<p>Enter the unique Certificate ID printed on the certificate.</p>

<form method="POST">
    <label>Certificate ID</label><br>
    <input 
        type="text" 
        name="certificate_uid" 
        placeholder="Example: CERT-DF3BF7EE" 
        required
    >

    <br><br>

    <button type="submit">Verify Certificate</button>
</form>

<br>

<?php if (!empty($message)): ?>
    <p style="color:red;"><?php echo $message; ?></p>
<?php endif; ?>

<?php if ($result): ?>

    <hr>

    <h2>Certificate Verified ✅</h2>

    <p><strong>Certificate ID:</strong> <?php echo $result["certificate_uid"]; ?></p>
    <p><strong>Student Name:</strong> <?php echo $result["student_name"]; ?></p>
    <p><strong>Certificate Type:</strong> <?php echo $result["certificate_type"]; ?></p>
    <p><strong>Course:</strong> <?php echo $result["course"]; ?></p>
    <p><strong>Issue Date:</strong> <?php echo $result["issue_date"]; ?></p>
    <p><strong>Status:</strong> <?php echo $result["status"]; ?></p>

    <?php if (!empty($result["file_path"])): ?>
        <h3>Certificate PDF</h3>
        <iframe 
            src="<?php echo $result["file_path"]; ?>" 
            width="100%" 
            height="600">
        </iframe>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>