<?php

$userAgent = strtolower($_SERVER["HTTP_USER_AGENT"] ?? "");

$isMobile = preg_match(
    "/mobile|android|iphone|ipad|tablet|blackberry|opera mini|windows phone/",
    $userAgent
);

if ($isMobile) {
    http_response_code(403);
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Desktop Only</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f4f7fb;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                text-align: center;
                margin: 0;
                padding: 20px;
            }
            .box {
                background: white;
                padding: 35px;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                max-width: 420px;
            }
            h2 {
                margin-bottom: 10px;
            }
            p {
                color: #555;
                line-height: 1.5;
            }
        </style>
    </head>
    <body>
        <div class='box'>
            <h2>Desktop Access Required</h2>
            <p>Admin panel is only accessible from desktop browsers.</p>
        </div>
    </body>
    </html>
    ";
    exit();
}

?>