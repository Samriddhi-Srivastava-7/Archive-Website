<?php

$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

$isMobile = preg_match('/mobile|android|iphone|ipad|tablet|blackberry|opera mini|windows phone/', $userAgent);

if ($isMobile) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Desktop Only</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f4f7fb;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                text-align: center;
            }
            .box {
                background: white;
                padding: 35px;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                max-width: 400px;
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