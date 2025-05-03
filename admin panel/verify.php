<?php
// verify.php

// ---- [ CORS HEADERS ] ----
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ---- [ MAIN FUNCTIONALITY ] ----

// Path to your licenses JSON file
$licenseFile = __DIR__ . '/licenses.json';

// Read JSON database
if (!file_exists($licenseFile)) {
    file_put_contents($licenseFile, '{}');
}
$licenses = json_decode(file_get_contents($licenseFile), true);

// Get incoming data
$input = json_decode(file_get_contents('php://input'), true);

$license_key = isset($input['license_key']) ? trim($input['license_key']) : '';
$device_id = isset($input['device_id']) ? trim($input['device_id']) : '';

if (empty($license_key) || empty($device_id)) {
    echo json_encode(['valid' => false, 'reason' => 'Missing license key or device ID']);
    exit;
}

// Check if license exists
if (!isset($licenses[$license_key])) {
    echo json_encode(['valid' => false, 'reason' => 'License not found']);
    exit;
}

// Check expiration
$expires_at = strtotime($licenses[$license_key]['expires_at']);
if ($expires_at < time()) {
    echo json_encode(['valid' => false, 'reason' => 'License expired']);
    exit;
}

// First activation (no device assigned yet)
if (empty($licenses[$license_key]['device_id'])) {
    $licenses[$license_key]['device_id'] = $device_id;
    file_put_contents($licenseFile, json_encode($licenses, JSON_PRETTY_PRINT));
    echo json_encode(['valid' => true, 'reason' => 'License activated']);
    exit;
}

// Check if device matches
if ($licenses[$license_key]['device_id'] === $device_id) {
    echo json_encode(['valid' => true, 'reason' => 'License valid']);
    exit;
} else {
    // Device mismatch
    echo json_encode(['valid' => false, 'reason' => 'License already activated on another device']);
    exit;
}
?>
