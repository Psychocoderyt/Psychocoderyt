<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP WebPhone</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>WebPhone</h1>
            <nav class="nav">
                <a href="?page=phone" class="active">Phone</a>
                <a href="?page=contacts">Contacts</a>
                <a href="?page=history">History</a>
                <a href="api/logout.php">Logout</a>
            </nav>
        </header>

        <main class="main">
            <div class="phone-container">
                <div class="display-section">
                    <div class="call-display">
                        <input type="text" id="phoneNumber" placeholder="Enter phone number or user ID" class="phone-input">
                        <div id="callStatus" class="call-status">Ready</div>
                        <div id="callTimer" class="call-timer">00:00</div>
                    </div>
                    
                    <div class="video-section">
                        <video id="localVideo" autoplay muted class="local-video"></video>
                        <video id="remoteVideo" autoplay class="remote-video"></video>
                    </div>
                </div>

                <div class="controls-section">
                    <div class="call-controls">
                        <button id="callBtn" class="btn btn-call">📞 Call</button>
                        <button id="hangupBtn" class="btn btn-hangup" disabled>📞 Hang Up</button>
                        <button id="muteBtn" class="btn btn-mute">🔊 Mute</button>
                        <button id="videoBtn" class="btn btn-video">📷 Video</button>
                    </div>

                    <div class="dialpad">
                        <div class="dialpad-row">
                            <button class="dialpad-btn" data-digit="1">1</button>
                            <button class="dialpad-btn" data-digit="2">2<span>ABC</span></button>
                            <button class="dialpad-btn" data-digit="3">3<span>DEF</span></button>
                        </div>
                        <div class="dialpad-row">
                            <button class="dialpad-btn" data-digit="4">4<span>GHI</span></button>
                            <button class="dialpad-btn" data-digit="5">5<span>JKL</span></button>
                            <button class="dialpad-btn" data-digit="6">6<span>MNO</span></button>
                        </div>
                        <div class="dialpad-row">
                            <button class="dialpad-btn" data-digit="7">7<span>PQRS</span></button>
                            <button class="dialpad-btn" data-digit="8">8<span>TUV</span></button>
                            <button class="dialpad-btn" data-digit="9">9<span>WXYZ</span></button>
                        </div>
                        <div class="dialpad-row">
                            <button class="dialpad-btn" data-digit="*">*</button>
                            <button class="dialpad-btn" data-digit="0">0</button>
                            <button class="dialpad-btn" data-digit="#">#</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="incoming-call" id="incomingCall" style="display: none;">
                <div class="incoming-call-info">
                    <h3>Incoming Call</h3>
                    <p id="callerInfo">Unknown Caller</p>
                    <div class="incoming-call-controls">
                        <button id="answerBtn" class="btn btn-answer">Answer</button>
                        <button id="rejectBtn" class="btn btn-reject">Reject</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/webrtc.js"></script>
    <script src="assets/js/phone.js"></script>
</body>
</html>