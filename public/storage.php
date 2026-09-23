<?php

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

if (str_starts_with($requestUri, $scriptDir . '/storage/')) {
    $relative = substr($requestUri, strlen($scriptDir . '/storage/'));
} elseif (str_starts_with($requestUri, '/storage/')) {
    $relative = substr($requestUri, 9);
} else {
    http_response_code(404);
    echo 'Not found';
    exit;
}

$file = __DIR__ . '/../storage/app/public/' . $relative;

if ($relative === false || $relative === '' || !file_exists($file) || is_dir($file)) {
    http_response_code(404);
    echo 'Not found';
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file);
finfo_close($finfo);

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($file));
readfile($file);
