<?php
header('Content-Type: application/json');

// Path to licenses.json file
$file = 'licenses.json';

// If licenses.json does not exist, create an empty one
if (!file_exists($file)) {
    file_put_contents($file, '{}');
}

// Load existing licenses
$licenses = json_decode(file_get_contents($file), true);

// Read input JSON from frontend
$input = json_decode(file_get_contents('php://input'), true);

// Check if action is set
if (!isset($input['action'])) {
    echo json_encode(['success' => false, 'message' => 'Missing action']);
    exit;
}

// Get the action type
$action = $input['action'];

// Handle Generate License action
if ($action === 'generate') {
    $license_key = $input['license_key'] ?? null;
    $validity = $input['validity'] ?? null;

    if (!$license_key || !$validity) {
        echo json_encode(['success' => false, 'message' => 'Missing license key or validity']);
        exit;
    }

    // Set expiry date
    if ($validity === 'lifetime') {
        $expires_at = '2099-12-31 23:59:59';
    } else {
        $expires_at = date('Y-m-d H:i:s', strtotime("+$validity months"));
    }

    // Add new license
    $licenses[$license_key] = [
        'device_id' => '',
        'expires_at' => $expires_at,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Save to file
    file_put_contents($file, json_encode($licenses, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
    exit;
}

// Handle if license key is missing
if (!isset($input['license_key']) || !isset($licenses[$input['license_key']])) {
    echo json_encode(['success' => false, 'message' => 'License key not found']);
    exit;
}

// Fetch current license
$license_key = $input['license_key'];

// Handle Edit Validity
if ($action === 'edit') {
    $validity = $input['validity'] ?? null;
    if (!$validity) {
        echo json_encode(['success' => false, 'message' => 'Missing validity']);
        exit;
    }

    if ($validity === 'lifetime') {
        $expires_at = '2099-12-31 23:59:59';
    } else {
        $expires_at = date('Y-m-d H:i:s', strtotime("+$validity months"));
    }

    $licenses[$license_key]['expires_at'] = $expires_at;
    file_put_contents($file, json_encode($licenses, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
    exit;
}

// Handle Delete License
if ($action === 'delete') {
    unset($licenses[$license_key]);
    file_put_contents($file, json_encode($licenses, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
    exit;
}

// If invalid action
echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>
