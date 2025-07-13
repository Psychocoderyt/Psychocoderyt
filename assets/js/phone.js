document.addEventListener('DOMContentLoaded', () => {
    const phoneNumberInput = document.getElementById('phoneNumber');
    const callBtn = document.getElementById('callBtn');
    const hangupBtn = document.getElementById('hangupBtn');
    const muteBtn = document.getElementById('muteBtn');
    const videoBtn = document.getElementById('videoBtn');
    const answerBtn = document.getElementById('answerBtn');
    const rejectBtn = document.getElementById('rejectBtn');
    const dialpadBtns = document.querySelectorAll('.dialpad-btn');
    
    // Dialpad functionality
    dialpadBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const digit = btn.dataset.digit;
            phoneNumberInput.value += digit;
            
            // Play DTMF tone if in call
            if (webrtcPhone && webrtcPhone.isCallActive) {
                playDTMFTone(digit);
            }
        });
    });
    
    // Call button
    callBtn.addEventListener('click', () => {
        const phoneNumber = phoneNumberInput.value.trim();
        if (phoneNumber) {
            makeCall(phoneNumber);
        } else {
            alert('Please enter a phone number or user ID');
        }
    });
    
    // Hangup button
    hangupBtn.addEventListener('click', () => {
        if (webrtcPhone) {
            webrtcPhone.hangupCall();
        }
    });
    
    // Mute button
    muteBtn.addEventListener('click', () => {
        if (webrtcPhone) {
            const isMuted = webrtcPhone.toggleMute();
            muteBtn.textContent = isMuted ? '🔇 Unmute' : '🔊 Mute';
            muteBtn.classList.toggle('active', isMuted);
        }
    });
    
    // Video button
    videoBtn.addEventListener('click', () => {
        if (webrtcPhone) {
            const isVideoEnabled = webrtcPhone.toggleVideo();
            videoBtn.textContent = isVideoEnabled ? '📷 Video' : '📷 Video Off';
            videoBtn.classList.toggle('active', !isVideoEnabled);
        }
    });
    
    // Answer button
    answerBtn.addEventListener('click', () => {
        answerCall();
    });
    
    // Reject button
    rejectBtn.addEventListener('click', () => {
        rejectCall();
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !webrtcPhone.isCallActive) {
            callBtn.click();
        } else if (e.key === 'Escape') {
            hangupBtn.click();
        } else if (e.key === 'm' || e.key === 'M') {
            muteBtn.click();
        } else if (e.key === 'v' || e.key === 'V') {
            videoBtn.click();
        }
    });
    
    // Allow phone number input to accept only digits and special characters
    phoneNumberInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^0-9+\-\s\(\)]/g, '');
    });
    
    // Auto-focus on phone number input
    phoneNumberInput.focus();
});

function makeCall(phoneNumber) {
    if (webrtcPhone) {
        webrtcPhone.makeCall(phoneNumber);
        
        // Log call attempt
        logCallHistory(phoneNumber, 'outgoing', 'attempting');
    }
}

function answerCall() {
    if (webrtcPhone) {
        // In a real implementation, you would get the offer from the signaling server
        // For demo purposes, we'll simulate answering
        const incomingCall = document.getElementById('incomingCall');
        incomingCall.style.display = 'none';
        
        // Simulate call connection
        setTimeout(() => {
            webrtcPhone.onCallConnected();
        }, 1000);
        
        // Log call
        logCallHistory('incoming_caller', 'incoming', 'answered');
    }
}

function rejectCall() {
    const incomingCall = document.getElementById('incomingCall');
    incomingCall.style.display = 'none';
    
    // Log call
    logCallHistory('incoming_caller', 'incoming', 'rejected');
}

function playDTMFTone(digit) {
    // DTMF frequencies
    const dtmfFreqs = {
        '1': [697, 1209], '2': [697, 1336], '3': [697, 1477],
        '4': [770, 1209], '5': [770, 1336], '6': [770, 1477],
        '7': [852, 1209], '8': [852, 1336], '9': [852, 1477],
        '*': [941, 1209], '0': [941, 1336], '#': [941, 1477]
    };
    
    const frequencies = dtmfFreqs[digit];
    if (frequencies) {
        playTone(frequencies[0], frequencies[1], 200);
    }
}

function playTone(freq1, freq2, duration) {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator1 = audioContext.createOscillator();
    const oscillator2 = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator1.frequency.value = freq1;
    oscillator2.frequency.value = freq2;
    
    oscillator1.connect(gainNode);
    oscillator2.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    gainNode.gain.value = 0.1;
    
    oscillator1.start();
    oscillator2.start();
    
    setTimeout(() => {
        oscillator1.stop();
        oscillator2.stop();
    }, duration);
}

function logCallHistory(number, type, status) {
    const callData = {
        number: number,
        type: type,
        status: status,
        timestamp: new Date().toISOString()
    };
    
    fetch('api/log_call.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(callData)
    }).catch(error => {
        console.error('Error logging call:', error);
    });
}

// Contact management functions
function addContact() {
    const name = prompt('Enter contact name:');
    const number = prompt('Enter phone number:');
    
    if (name && number) {
        const contactData = {
            name: name,
            number: number
        };
        
        fetch('api/add_contact.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(contactData)
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to add contact');
            }
        }).catch(error => {
            console.error('Error adding contact:', error);
        });
    }
}

function callContact(number) {
    // Focus on phone page and fill number
    if (window.location.search.indexOf('page=phone') === -1) {
        window.location.href = '?page=phone';
    }
    
    const phoneNumberInput = document.getElementById('phoneNumber');
    if (phoneNumberInput) {
        phoneNumberInput.value = number;
    }
    
    makeCall(number);
}

// Utility functions
function formatPhoneNumber(number) {
    // Simple phone number formatting
    return number.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
}

function formatCallDuration(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
}

function formatCallTime(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleString();
}

// Initialize contact search functionality
function initContactSearch() {
    const searchInput = document.getElementById('contactSearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const contacts = document.querySelectorAll('.contact-item');
            
            contacts.forEach(contact => {
                const name = contact.querySelector('.contact-name').textContent.toLowerCase();
                const number = contact.querySelector('.contact-number').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || number.includes(searchTerm)) {
                    contact.style.display = 'flex';
                } else {
                    contact.style.display = 'none';
                }
            });
        });
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    initContactSearch();
});