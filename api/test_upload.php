<?php
session_start();
header('Content-Type: application/json');

$fileArr = $_FILES['plant-image'] ?? null;
if (!$fileArr) {
    echo json_encode(['error' => 'No file received']);
    exit;
}

$result = [];
$result['file_received'] = true;
$result['error_code'] = $fileArr['error'];
$result['tmp_name'] = $fileArr['tmp_name'];
$result['tmp_exists'] = file_exists($fileArr['tmp_name']);

$image_info = getimagesize($fileArr['tmp_name']);
$result['getimagesize'] = $image_info !== false ? 'OK (type=' . $image_info[2] . ')' : 'FAILED';

$upload_dir = '../assets/images/plants/';
$result['upload_dir_exists'] = is_dir($upload_dir);
$result['upload_dir_writable'] = is_writable($upload_dir);

if (!is_dir($upload_dir)) {
    $made = mkdir($upload_dir, 0755, true);
    $result['mkdir_result'] = $made;
}

$file_name = time() . '_test.png';
$target_file = $upload_dir . $file_name;
$move_result = move_uploaded_file($fileArr['tmp_name'], $target_file);
$result['move_result'] = $move_result;
$result['target_file'] = $target_file;
$result['target_exists'] = file_exists($target_file);
$result['saved_path'] = 'assets/images/plants/' . $file_name;

echo json_encode($result, JSON_PRETTY_PRINT);
