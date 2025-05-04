<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Set timezone to Indian Standard Time
date_default_timezone_set('Asia/Kolkata');

$licenseFile = __DIR__ . '/licenses.json';
$logFile = __DIR__ . '/logs.json';
$debugFile = __DIR__ . '/debug.log';

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

$licenseKey = isset($input['license']) ? trim($input['license']) : '';
$userIP = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');

// Debug logging
file_put_contents($debugFile, "Received license: $licenseKey | IP: $userIP | Time: $timestamp\n", FILE_APPEND);

$licenses = file_exists($licenseFile) ? json_decode(file_get_contents($licenseFile), true) : [];
$logs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
if (!is_array($logs)) $logs = [];

$response = ['status' => 'error', 'message' => 'Invalid or Expired License'];

if (isset($licenses[$licenseKey])) {
    $license = &$licenses[$licenseKey];

    // Check expiration
    if (strtotime($license['expires_at']) < time()) {
        $response['message'] = 'License Expired';
        $license['status'] = 'expired';
    } else {
        // Check IP binding
        if (!empty($license['ip']) && $license['ip'] !== $userIP) {
            // If IP is different, force reauthorization
            $response = [
                'status' => 'error',
                'message' => 'IP Changed - Reauthorization Required',
                'force_reauth' => true
            ];
            
            // Log the IP change attempt
            $logs[] = [
                'license' => $licenseKey,
                'old_ip' => $license['ip'],
                'new_ip' => $userIP,
                'timestamp' => $timestamp,
                'event' => 'ip_change_attempt'
            ];
        } else {
            // Update IP and access time
            $oldIP = $license['ip'] ?? null;
            $license['ip'] = $userIP;
            $license['last_access'] = $timestamp;
            $license['status'] = 'active';

            $response = [
                'status' => 'success',
                'message' => 'License Verified'
            ];

            // Log successful verification
            $logs[] = [
                'license' => $licenseKey,
                'ip' => $userIP,
                'timestamp' => $timestamp,
                'event' => 'verification',
                'status' => 'success'
            ];

            // If this is a new IP, log the change
            if ($oldIP && $oldIP !== $userIP) {
                $logs[] = [
                    'license' => $licenseKey,
                    'old_ip' => $oldIP,
                    'new_ip' => $userIP,
                    'timestamp' => $timestamp,
                    'event' => 'ip_change'
                ];
            }
        }
    }

    // Save updated licenses.json
    file_put_contents($licenseFile, json_encode($licenses, JSON_PRETTY_PRINT));
} else {
    // Log invalid license attempt
    $logs[] = [
        'license' => $licenseKey,
        'ip' => $userIP,
        'timestamp' => $timestamp,
        'event' => 'invalid_license_attempt'
    ];
}

// Save logs
file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));

header('Content-Type: application/json');
echo json_encode($response);
?>
