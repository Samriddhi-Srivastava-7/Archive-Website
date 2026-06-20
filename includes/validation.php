<?php

function cleanInput($value)
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function validateRequired($value, $fieldName, &$errors)
{
    if (empty(trim($value))) {
        $errors[] = $fieldName . " is required.";
    }
}

function validateMaxLength($value, $fieldName, $max, &$errors)
{
    if (strlen(trim($value)) > $max) {
        $errors[] = $fieldName . " must be less than " . $max . " characters.";
    }
}

function validateDateInput($date, &$errors)
{
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    if (!$dateTime || $dateTime->format('Y-m-d') !== $date) {
        $errors[] = "Issue date is invalid.";
    }
}

function validateStatus($status, &$errors)
{
    $allowedStatus = ["Draft", "Published", "Revoked"];

    if (!in_array($status, $allowedStatus)) {
        $errors[] = "Invalid certificate status.";
    }
}

function validateId($id, &$errors)
{
    if (!isset($id) || !ctype_digit((string)$id)) {
        $errors[] = "Invalid record ID.";
    }
}

function validateCertificatePDF($file, &$errors)
{
    if (!isset($file) || $file["error"] !== UPLOAD_ERR_OK) {
        $errors[] = "Certificate PDF is required.";
        return;
    }

    $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    if ($fileExtension !== "pdf") {
        $errors[] = "Only PDF files are allowed.";
    }

    if ($file["size"] > 5 * 1024 * 1024) {
        $errors[] = "File size should be less than 5MB.";
    }
}

function validateCertificateForm($data, &$errors)
{
    validateRequired($data["student_name"] ?? "", "Student name", $errors);
    validateRequired($data["certificate_type"] ?? "", "Certificate type", $errors);
    validateRequired($data["course"] ?? "", "Course / Role", $errors);
    validateRequired($data["issue_date"] ?? "", "Issue date", $errors);

    validateMaxLength($data["student_name"] ?? "", "Student name", 150, $errors);
    validateMaxLength($data["certificate_type"] ?? "", "Certificate type", 100, $errors);
    validateMaxLength($data["course"] ?? "", "Course / Role", 150, $errors);

    validateDateInput($data["issue_date"] ?? "", $errors);
}
?>