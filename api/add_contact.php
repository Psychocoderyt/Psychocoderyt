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
$name = $input['name'] ?? '';
$number = $input['number'] ?? '';

if (empty($name) || empty($number)) {
    echo json_encode(['success' => false, 'error' => 'Name and number are required']);
    exit;
}

$userId = $_SESSION['user_id'];
$userFile = '../data/' . $userId . '.json';

if (file_exists($userFile)) {
    $userData = json_decode(file_get_contents($userFile), true);
    
    // Check if contact already exists
    foreach ($userData['contacts'] as $contact) {
        if ($contact['number'] === $number) {
            echo json_encode(['success' => false, 'error' => 'Contact already exists']);
            exit;
        }
    }
    
    // Add new contact
    $userData['contacts'][] = [
        'name' => $name,
        'number' => $number
    ];
    
    file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'User data not found']);
}
?>