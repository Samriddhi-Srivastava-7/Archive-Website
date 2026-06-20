<?php

require_once __DIR__ . "/env.php";

function uploadCertificateFile($fileTmpPath, $newFileName)
{
    $storageDriver = $_ENV["STORAGE_DRIVER"] ?? "local";

    if ($storageDriver === "local") {
        return uploadToLocalStorage($fileTmpPath, $newFileName);
    }

    return false;
}

function uploadToLocalStorage($fileTmpPath, $newFileName)
{
    $uploadDir = __DIR__ . "/../uploads/certificates/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $destinationPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destinationPath)) {
        return [
            "file_name" => $newFileName,
            "file_path" => "uploads/certificates/" . $newFileName,
            "storage_driver" => "local"
        ];
    }

    return false;
}
?>