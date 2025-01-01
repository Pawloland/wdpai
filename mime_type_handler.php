<?php
require_once 'debug_utils.php';

$requestUri = $_SERVER['REQUEST_URI'];
$filePath = $_SERVER['DOCUMENT_ROOT'] . $requestUri;

debug_log("Requested URI: $requestUri");

if (!file_exists($filePath) || !is_file($filePath)) {
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/public/img/assets/blank-poster.png';
}
debug_log("Resolved file path: $filePath");

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filePath);
finfo_close($finfo);
if (!$mimeType) {
    $mimeType = mime_content_type($filePath);
}
debug_log($mimeType);

// Serve the file with the correct MIME type
header("Content-Type: $mimeType");
readfile($filePath);

