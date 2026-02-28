<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['action']) || !isset($input['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$allowed_actions = ['thumbs_up', 'thumbs_down', 'save', 'undo_rating', 'undo_save'];
if (!in_array($input['action'], $allowed_actions)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
    exit();
}

$url        = $input['url'];
$action     = $input['action'];
$title      = $input['title'] ?? '';
$brief_date = $input['brief_date'] ?? '';

$log_file = __DIR__ . '/feedback.json';
$log = [];

if (file_exists($log_file)) {
    $existing = file_get_contents($log_file);
    $log = json_decode($existing, true) ?? [];
}

// Initialise entry for this URL if it doesn't exist
if (!isset($log[$url])) {
    $log[$url] = [
        'title'      => $title,
        'brief_date' => $brief_date,
        'rating'     => null,
        'saved'      => false,
        'last_updated' => date('c'),
    ];
}

// Apply action
if ($action === 'thumbs_up' || $action === 'thumbs_down') {
    $log[$url]['rating'] = $action;
} elseif ($action === 'undo_rating') {
    $log[$url]['rating'] = null;
} elseif ($action === 'save') {
    $log[$url]['saved'] = true;
} elseif ($action === 'undo_save') {
    $log[$url]['saved'] = false;
}

$log[$url]['last_updated'] = date('c');

// Remove entry entirely if no rating and not saved
if ($log[$url]['rating'] === null && $log[$url]['saved'] === false) {
    unset($log[$url]);
}

file_put_contents($log_file, json_encode($log, JSON_PRETTY_PRINT));

echo json_encode(['success' => true]);
?>
