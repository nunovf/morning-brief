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

$entry = [
    'action'     => $input['action'],
    'url'        => $input['url'],
    'title'      => $input['title'] ?? '',
    'brief_date' => $input['brief_date'] ?? '',
    'timestamp'  => date('c'),
];

$log_file = __DIR__ . '/feedback.json';
$log = [];

if (file_exists($log_file)) {
    $existing = file_get_contents($log_file);
    $log = json_decode($existing, true) ?? [];
}

$log[] = $entry;

file_put_contents($log_file, json_encode($log, JSON_PRETTY_PRINT));

echo json_encode(['success' => true]);
?>
