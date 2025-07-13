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
$from = $input['from'] ?? '';
$message = $input['message'] ?? [];

if (empty($from) || empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// In a real implementation, this would route messages to the target user
// For demo purposes, we'll just log the signaling data
$signalingDir = '../data/signaling';
if (!file_exists($signalingDir)) {
    mkdir($signalingDir, 0777, true);
}

$signalingFile = $signalingDir . '/' . $from . '_' . time() . '.json';
file_put_contents($signalingFile, json_encode([
    'from' => $from,
    'message' => $message,
    'timestamp' => date('c')
], JSON_PRETTY_PRINT));

echo json_encode(['success' => true]);
?>