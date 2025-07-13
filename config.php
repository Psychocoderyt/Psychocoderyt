<?php
// WebPhone Configuration

// Database configuration (for demo, using file-based storage)
define('DATA_DIR', 'data/');
define('SIGNALING_DIR', 'data/signaling/');

// Session configuration
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400);

// WebRTC configuration
$webrtc_config = [
    'iceServers' => [
        ['urls' => 'stun:stun.l.google.com:19302'],
        ['urls' => 'stun:stun1.l.google.com:19302'],
        ['urls' => 'stun:stun2.l.google.com:19302']
    ]
];

// Application settings
define('MAX_CALL_HISTORY', 100);
define('MAX_CONTACTS', 500);
define('INCOMING_CALL_PROBABILITY', 2); // 2% chance per poll

// Security settings
define('LOGIN_ATTEMPTS_MAX', 5);
define('LOGIN_LOCKOUT_TIME', 300); // 5 minutes

// Demo users (in production, use proper database and password hashing)
$demo_users = [
    'demo' => [
        'password' => 'demo123',
        'name' => 'Demo User'
    ],
    'user1' => [
        'password' => 'password1',
        'name' => 'User One'
    ],
    'user2' => [
        'password' => 'password2',
        'name' => 'User Two'
    ]
];

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>