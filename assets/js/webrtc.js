class WebRTCPhone {
    constructor() {
        this.localStream = null;
        this.remoteStream = null;
        this.peerConnection = null;
        this.isCallActive = false;
        this.isMuted = false;
        this.isVideoEnabled = true;
        this.callStartTime = null;
        this.callTimer = null;
        this.socket = null;
        this.userId = null;
        
        // ICE servers configuration
        this.iceServers = {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' },
                { urls: 'stun:stun1.l.google.com:19302' }
            ]
        };
        
        this.init();
    }
    
    async init() {
        try {
            // Get user media
            this.localStream = await navigator.mediaDevices.getUserMedia({
                audio: true,
                video: true
            });
            
            // Display local video
            const localVideo = document.getElementById('localVideo');
            if (localVideo) {
                localVideo.srcObject = this.localStream;
            }
            
            // Initialize WebSocket connection for signaling
            this.initWebSocket();
            
        } catch (error) {
            console.error('Error accessing media devices:', error);
            this.handleError('Unable to access camera/microphone. Please check permissions.');
        }
    }
    
    initWebSocket() {
        // For demo purposes, we'll use a simple polling mechanism
        // In production, you would use WebSocket or Socket.IO
        this.userId = 'user_' + Math.random().toString(36).substr(2, 9);
        console.log('User ID:', this.userId);
        
        // Start polling for incoming calls
        this.startPolling();
    }
    
    startPolling() {
        // Poll for incoming calls every 2 seconds
        setInterval(() => {
            this.checkForIncomingCalls();
        }, 2000);
    }
    
    async checkForIncomingCalls() {
        try {
            const response = await fetch('api/check_calls.php');
            const data = await response.json();
            
            if (data.incoming_call && !this.isCallActive) {
                this.showIncomingCall(data.caller_id);
            }
        } catch (error) {
            console.error('Error checking for calls:', error);
        }
    }
    
    showIncomingCall(callerId) {
        const incomingCall = document.getElementById('incomingCall');
        const callerInfo = document.getElementById('callerInfo');
        
        callerInfo.textContent = `Call from: ${callerId}`;
        incomingCall.style.display = 'flex';
        
        // Play ring tone (you can add actual audio file)
        console.log('Incoming call from:', callerId);
    }
    
    async createPeerConnection() {
        this.peerConnection = new RTCPeerConnection(this.iceServers);
        
        // Add local stream to peer connection
        this.localStream.getTracks().forEach(track => {
            this.peerConnection.addTrack(track, this.localStream);
        });
        
        // Handle remote stream
        this.peerConnection.ontrack = (event) => {
            const remoteVideo = document.getElementById('remoteVideo');
            if (remoteVideo) {
                remoteVideo.srcObject = event.streams[0];
            }
        };
        
        // Handle ICE candidates
        this.peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                this.sendSignalingMessage({
                    type: 'ice-candidate',
                    candidate: event.candidate
                });
            }
        };
        
        // Handle connection state changes
        this.peerConnection.onconnectionstatechange = () => {
            console.log('Connection state:', this.peerConnection.connectionState);
            if (this.peerConnection.connectionState === 'connected') {
                this.onCallConnected();
            } else if (this.peerConnection.connectionState === 'disconnected') {
                this.onCallDisconnected();
            }
        };
    }
    
    async makeCall(targetUserId) {
        try {
            await this.createPeerConnection();
            
            // Create offer
            const offer = await this.peerConnection.createOffer();
            await this.peerConnection.setLocalDescription(offer);
            
            // Send offer to target user
            this.sendSignalingMessage({
                type: 'offer',
                offer: offer,
                target: targetUserId
            });
            
            this.updateCallStatus('Calling...');
            
        } catch (error) {
            console.error('Error making call:', error);
            this.handleError('Failed to make call');
        }
    }
    
    async answerCall(offer) {
        try {
            await this.createPeerConnection();
            
            // Set remote description
            await this.peerConnection.setRemoteDescription(offer);
            
            // Create answer
            const answer = await this.peerConnection.createAnswer();
            await this.peerConnection.setLocalDescription(answer);
            
            // Send answer back
            this.sendSignalingMessage({
                type: 'answer',
                answer: answer
            });
            
            this.onCallConnected();
            
        } catch (error) {
            console.error('Error answering call:', error);
            this.handleError('Failed to answer call');
        }
    }
    
    async handleSignalingMessage(message) {
        switch (message.type) {
            case 'offer':
                await this.answerCall(message.offer);
                break;
                
            case 'answer':
                await this.peerConnection.setRemoteDescription(message.answer);
                this.onCallConnected();
                break;
                
            case 'ice-candidate':
                await this.peerConnection.addIceCandidate(message.candidate);
                break;
                
            case 'hangup':
                this.onCallDisconnected();
                break;
        }
    }
    
    sendSignalingMessage(message) {
        // In a real implementation, this would send via WebSocket
        // For demo purposes, we'll use HTTP API
        fetch('api/signaling.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                from: this.userId,
                message: message
            })
        });
    }
    
    hangupCall() {
        if (this.peerConnection) {
            this.peerConnection.close();
            this.peerConnection = null;
        }
        
        this.sendSignalingMessage({ type: 'hangup' });
        this.onCallDisconnected();
    }
    
    toggleMute() {
        if (this.localStream) {
            const audioTrack = this.localStream.getAudioTracks()[0];
            if (audioTrack) {
                audioTrack.enabled = !audioTrack.enabled;
                this.isMuted = !audioTrack.enabled;
                return this.isMuted;
            }
        }
        return false;
    }
    
    toggleVideo() {
        if (this.localStream) {
            const videoTrack = this.localStream.getVideoTracks()[0];
            if (videoTrack) {
                videoTrack.enabled = !videoTrack.enabled;
                this.isVideoEnabled = videoTrack.enabled;
                return this.isVideoEnabled;
            }
        }
        return false;
    }
    
    onCallConnected() {
        this.isCallActive = true;
        this.callStartTime = Date.now();
        this.updateCallStatus('Connected');
        this.startCallTimer();
        
        // Hide incoming call dialog
        const incomingCall = document.getElementById('incomingCall');
        if (incomingCall) {
            incomingCall.style.display = 'none';
        }
        
        // Enable hangup button
        const hangupBtn = document.getElementById('hangupBtn');
        if (hangupBtn) {
            hangupBtn.disabled = false;
        }
        
        // Disable call button
        const callBtn = document.getElementById('callBtn');
        if (callBtn) {
            callBtn.disabled = true;
        }
    }
    
    onCallDisconnected() {
        this.isCallActive = false;
        this.callStartTime = null;
        this.updateCallStatus('Ready');
        this.stopCallTimer();
        
        // Clear remote video
        const remoteVideo = document.getElementById('remoteVideo');
        if (remoteVideo) {
            remoteVideo.srcObject = null;
        }
        
        // Enable call button
        const callBtn = document.getElementById('callBtn');
        if (callBtn) {
            callBtn.disabled = false;
        }
        
        // Disable hangup button
        const hangupBtn = document.getElementById('hangupBtn');
        if (hangupBtn) {
            hangupBtn.disabled = true;
        }
        
        // Hide incoming call dialog
        const incomingCall = document.getElementById('incomingCall');
        if (incomingCall) {
            incomingCall.style.display = 'none';
        }
    }
    
    startCallTimer() {
        this.callTimer = setInterval(() => {
            if (this.callStartTime) {
                const elapsed = Date.now() - this.callStartTime;
                const minutes = Math.floor(elapsed / 60000);
                const seconds = Math.floor((elapsed % 60000) / 1000);
                const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                const timerElement = document.getElementById('callTimer');
                if (timerElement) {
                    timerElement.textContent = timeString;
                }
            }
        }, 1000);
    }
    
    stopCallTimer() {
        if (this.callTimer) {
            clearInterval(this.callTimer);
            this.callTimer = null;
        }
        
        const timerElement = document.getElementById('callTimer');
        if (timerElement) {
            timerElement.textContent = '00:00';
        }
    }
    
    updateCallStatus(status) {
        const statusElement = document.getElementById('callStatus');
        if (statusElement) {
            statusElement.textContent = status;
        }
    }
    
    handleError(message) {
        console.error(message);
        this.updateCallStatus(`Error: ${message}`);
    }
}

// Initialize WebRTC phone when page loads
let webrtcPhone = null;

document.addEventListener('DOMContentLoaded', () => {
    webrtcPhone = new WebRTCPhone();
});