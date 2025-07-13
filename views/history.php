<?php
$userId = $_SESSION['user_id'];
$userFile = 'data/' . $userId . '.json';
$callHistory = [];

if (file_exists($userFile)) {
    $userData = json_decode(file_get_contents($userFile), true);
    $callHistory = array_reverse($userData['call_history'] ?? []);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call History - PHP WebPhone</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>WebPhone</h1>
            <nav class="nav">
                <a href="?page=phone">Phone</a>
                <a href="?page=contacts">Contacts</a>
                <a href="?page=history" class="active">History</a>
                <a href="api/logout.php">Logout</a>
            </nav>
        </header>

        <main class="main">
            <div class="history-container">
                <h2>Call History</h2>

                <div class="history-list">
                    <?php if (empty($callHistory)): ?>
                        <p>No call history found. Make your first call!</p>
                    <?php else: ?>
                        <?php foreach ($callHistory as $call): ?>
                            <div class="history-item">
                                <div class="history-info">
                                    <div class="history-caller">
                                        <?php
                                        $icon = $call['type'] === 'incoming' ? '📞' : '📱';
                                        $statusIcon = $call['status'] === 'answered' ? '✅' : ($call['status'] === 'rejected' ? '❌' : '⏱️');
                                        echo $icon . ' ' . htmlspecialchars($call['number']);
                                        ?>
                                    </div>
                                    <div class="history-details">
                                        <span class="history-type"><?php echo ucfirst($call['type']); ?></span>
                                        <span class="history-status"><?php echo $statusIcon . ' ' . ucfirst($call['status']); ?></span>
                                    </div>
                                    <div class="history-time"><?php echo date('M j, Y g:i A', strtotime($call['timestamp'])); ?></div>
                                    <?php if ($call['duration'] > 0): ?>
                                        <div class="history-duration">Duration: <?php echo gmdate('i:s', $call['duration']); ?></div>
                                    <?php endif; ?>
                                </div>
                                <button onclick="callContact('<?php echo htmlspecialchars($call['number']); ?>')" class="btn-call-contact">Call Back</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/phone.js"></script>
    
    <style>
        .history-details {
            display: flex;
            gap: 15px;
            margin: 5px 0;
        }
        
        .history-type {
            color: #666;
            font-size: 0.9em;
        }
        
        .history-status {
            color: #666;
            font-size: 0.9em;
        }
        
        .history-time {
            color: #666;
            font-size: 0.8em;
        }
        
        .history-duration {
            color: #666;
            font-size: 0.8em;
        }
    </style>
</body>
</html>