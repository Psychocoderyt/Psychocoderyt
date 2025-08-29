<?php
// WebPhone Setup Script
echo "Setting up PHP WebPhone...\n";

// Create data directory if it doesn't exist
if (!file_exists('data')) {
    mkdir('data', 0755, true);
    echo "✓ Created data directory\n";
} else {
    echo "✓ Data directory already exists\n";
}

// Create signaling directory
if (!file_exists('data/signaling')) {
    mkdir('data/signaling', 0755, true);
    echo "✓ Created signaling directory\n";
} else {
    echo "✓ Signaling directory already exists\n";
}

// Check PHP version
$phpVersion = phpversion();
echo "✓ PHP Version: $phpVersion\n";

if (version_compare($phpVersion, '7.4.0', '<')) {
    echo "⚠ Warning: PHP 7.4+ recommended for best performance\n";
}

// Check required extensions
$requiredExtensions = ['json', 'session'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✓ $ext extension loaded\n";
    } else {
        echo "✗ $ext extension not loaded\n";
    }
}

// Check file permissions
if (is_writable('data')) {
    echo "✓ Data directory is writable\n";
} else {
    echo "✗ Data directory is not writable\n";
}

// Create demo user data
$demoUsers = ['demo', 'user1', 'user2'];
foreach ($demoUsers as $user) {
    $userFile = "data/$user.json";
    if (!file_exists($userFile)) {
        $userData = [
            'contacts' => [
                [
                    'name' => 'Emergency',
                    'number' => '911'
                ],
                [
                    'name' => 'Test Contact',
                    'number' => '555-TEST'
                ]
            ],
            'call_history' => []
        ];
        file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
        echo "✓ Created demo data for user: $user\n";
    }
}

echo "\nSetup complete!\n";
echo "Access the webphone at: http://localhost:8000\n";
echo "Demo credentials: demo / demo123\n";
?>