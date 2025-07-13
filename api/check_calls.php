<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// For demo purposes, randomly simulate incoming calls
$shouldRing = (rand(1, 100) <= 2); // 2% chance per poll

if ($shouldRing) {
    $callers = ['555-0123', '555-0456', '555-0789', 'user1', 'user2'];
    $randomCaller = $callers[array_rand($callers)];
    
    echo json_encode([
        'incoming_call' => true,
        'caller_id' => $randomCaller
    ]);
} else {
    echo json_encode([
        'incoming_call' => false
    ]);
}
?>