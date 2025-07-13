<?php
session_start();

// Simple user authentication (in production, use proper password hashing)
$users = [
    'demo' => 'demo123',
    'user1' => 'password1',
    'user2' => 'password2'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['user_id'] = $username;
        $_SESSION['logged_in'] = true;
        
        // Initialize user database if needed
        initUserDatabase($username);
        
        header('Location: ../index.php?page=phone');
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid username or password';
        header('Location: ../index.php?page=login');
        exit;
    }
}

function initUserDatabase($username) {
    $dataDir = '../data';
    if (!file_exists($dataDir)) {
        mkdir($dataDir, 0777, true);
    }
    
    $userFile = $dataDir . '/' . $username . '.json';
    if (!file_exists($userFile)) {
        $userData = [
            'contacts' => [
                [
                    'name' => 'John Doe',
                    'number' => '555-0123'
                ],
                [
                    'name' => 'Jane Smith',
                    'number' => '555-0456'
                ]
            ],
            'call_history' => []
        ];
        
        file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
    }
}
?>