<?php

function loadEnv($path)
{
    if (!file_exists($path)) {
        die("Environment file not found.");
    }

    $lines = file(
        $path,
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    );

    foreach ($lines as $line) {

        $line = trim($line);

        // Skip comments and empty lines
        if (
            empty($line) ||
            str_starts_with($line, "#")
        ) {
            continue;
        }

        // Skip invalid lines
        if (!str_contains($line, "=")) {
            continue;
        }

        [$key, $value] = explode("=", $line, 2);

        $key = trim($key);
        $value = trim($value);

        // Store in PHP environment
        $_ENV[$key] = $value;

        // Store in system environment
        putenv("$key=$value");
    }
}

loadEnv(__DIR__ . "/../.env");

?>