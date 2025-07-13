<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$number = $input['number'] ?? '';
$type = $input['type'] ?? '';
$status = $input['status'] ?? '';
$timestamp = $input['timestamp'] ?? date('c');

if (empty($number) || empty($type) || empty($status)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$userId = $_SESSION['user_id'];
$userFile = '../data/' . $userId . '.json';

if (file_exists($userFile)) {
    $userData = json_decode(file_get_contents($userFile), true);
    
    // Add call to history
    $userData['call_history'][] = [
        'number' => $number,
        'type' => $type,
        'status' => $status,
        'timestamp' => $timestamp,
        'duration' => 0 // Will be updated when call ends
    ];
    
    // Keep only last 100 calls
    if (count($userData['call_history']) > 100) {
        $userData['call_history'] = array_slice($userData['call_history'], -100);
    }
    
    file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'User data not found']);
}
?>