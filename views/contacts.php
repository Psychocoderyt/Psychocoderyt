<?php
$userId = $_SESSION['user_id'];
$userFile = 'data/' . $userId . '.json';
$contacts = [];

if (file_exists($userFile)) {
    $userData = json_decode(file_get_contents($userFile), true);
    $contacts = $userData['contacts'] ?? [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts - PHP WebPhone</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>WebPhone</h1>
            <nav class="nav">
                <a href="?page=phone">Phone</a>
                <a href="?page=contacts" class="active">Contacts</a>
                <a href="?page=history">History</a>
                <a href="api/logout.php">Logout</a>
            </nav>
        </header>

        <main class="main">
            <div class="contacts-container">
                <div class="contacts-header">
                    <h2>Contacts</h2>
                    <div class="contacts-actions">
                        <input type="text" id="contactSearch" placeholder="Search contacts..." style="margin-right: 10px; padding: 8px;">
                        <button onclick="addContact()" class="btn btn-call">Add Contact</button>
                    </div>
                </div>

                <div class="contacts-list">
                    <?php if (empty($contacts)): ?>
                        <p>No contacts found. <a href="#" onclick="addContact()">Add your first contact</a></p>
                    <?php else: ?>
                        <?php foreach ($contacts as $contact): ?>
                            <div class="contact-item">
                                <div class="contact-info">
                                    <div class="contact-name"><?php echo htmlspecialchars($contact['name']); ?></div>
                                    <div class="contact-number"><?php echo htmlspecialchars($contact['number']); ?></div>
                                </div>
                                <button onclick="callContact('<?php echo htmlspecialchars($contact['number']); ?>')" class="btn-call-contact">Call</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/phone.js"></script>
    
    <style>
        .contacts-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .contacts-actions {
            display: flex;
            align-items: center;
        }
        
        .contacts-actions input {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        
        .contacts-actions input:focus {
            border-color: #667eea;
        }
    </style>
</body>
</html>