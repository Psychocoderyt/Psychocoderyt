# PHP WebPhone

A complete web-based phone application built with PHP, HTML5, CSS3, and JavaScript featuring WebRTC for real-time communication.

## Features

- **WebRTC Integration**: Browser-to-browser voice and video calling
- **Modern UI**: Responsive design with gradient backgrounds and smooth animations
- **User Authentication**: Session-based login system
- **Contact Management**: Add, search, and manage contacts
- **Call History**: Track incoming and outgoing calls
- **DTMF Tones**: Dialpad with realistic phone tones
- **Call Controls**: Mute, video toggle, and call management
- **Real-time Updates**: Live call status and timer

## Demo Credentials

- **Username**: demo
- **Password**: demo123

Additional test users:
- user1 / password1
- user2 / password2

## Installation

1. **Clone or download** the project files to your web server directory
2. **Ensure PHP 7.4+** is installed on your server
3. **Set permissions** for the data directory:
   ```bash
   chmod 755 data/
   ```
4. **Access the application** via your web browser

## File Structure

```
webphone/
├── index.php              # Main entry point
├── config.php             # Configuration file
├── README.md              # This file
├── views/
│   ├── phone.php          # Main phone interface
│   ├── login.php          # Login page
│   ├── contacts.php       # Contacts management
│   └── history.php        # Call history
├── api/
│   ├── login.php          # Authentication handler
│   ├── logout.php         # Logout handler
│   ├── add_contact.php    # Contact management
│   ├── log_call.php       # Call logging
│   ├── check_calls.php    # Incoming call checker
│   └── signaling.php      # WebRTC signaling
├── assets/
│   ├── css/
│   │   └── style.css      # Main stylesheet
│   └── js/
│       ├── webrtc.js      # WebRTC functionality
│       └── phone.js       # UI interactions
└── data/                  # User data storage (auto-created)
```

## Usage

### Making Calls

1. **Login** using the demo credentials
2. **Enter a phone number** or user ID in the input field
3. **Click Call** or press Enter
4. **Use the dialpad** for DTMF tones during calls
5. **Control the call** with mute, video, and hangup buttons

### Managing Contacts

1. **Navigate to Contacts** from the main menu
2. **Add contacts** using the "Add Contact" button
3. **Search contacts** using the search field
4. **Call contacts** directly from the contact list

### Call History

1. **View call history** from the main menu
2. **See call details** including type, status, and duration
3. **Call back** any number from the history

## WebRTC Features

- **Peer-to-peer communication** using WebRTC
- **STUN server integration** for NAT traversal
- **Audio and video streams** with local/remote display
- **Call state management** with proper connection handling
- **Signaling server** for offer/answer exchange

## Browser Compatibility

- **Chrome 90+** (Recommended)
- **Firefox 88+**
- **Safari 14+**
- **Edge 90+**

**Note**: WebRTC requires HTTPS in production environments.

## Security Considerations

### For Production Use:

1. **Use HTTPS** - WebRTC requires secure connections
2. **Implement proper authentication** with password hashing
3. **Use a real database** instead of file-based storage
4. **Set up proper WebSocket** signaling server
5. **Implement rate limiting** for API calls
6. **Add input validation** and sanitization
7. **Configure proper CORS** headers
8. **Use environment variables** for configuration

## Technical Details

### WebRTC Implementation

- **RTCPeerConnection** for peer-to-peer communication
- **getUserMedia** for accessing camera/microphone
- **WebRTC signaling** via HTTP polling (demo only)
- **ICE candidates** for connection establishment
- **DTMF tone generation** using Web Audio API

### Data Storage

- **JSON files** for user data (demo only)
- **Session-based** authentication
- **File-based** contact and call history storage

### API Endpoints

- `POST /api/login.php` - User authentication
- `GET /api/logout.php` - User logout
- `POST /api/add_contact.php` - Add new contact
- `POST /api/log_call.php` - Log call history
- `GET /api/check_calls.php` - Check for incoming calls
- `POST /api/signaling.php` - WebRTC signaling

## Customization

### Adding New Features

1. **SIP Integration**: Add SIP.js for real phone calls
2. **WebSocket Signaling**: Implement real-time signaling
3. **Database Integration**: Use MySQL/PostgreSQL
4. **User Registration**: Add user signup functionality
5. **Group Calls**: Multi-party calling support

### Styling

- Modify `assets/css/style.css` for custom themes
- Update color scheme in CSS variables
- Add custom animations and transitions

## Troubleshooting

### Common Issues

1. **Camera/Microphone Access**: Ensure browser permissions are granted
2. **WebRTC Connection**: Check firewall and NAT settings
3. **File Permissions**: Ensure data directory is writable
4. **PHP Session**: Check session configuration
5. **HTTPS Required**: WebRTC needs secure connections in production

### Debug Mode

Enable debug mode in `config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## License

This project is open source and available under the MIT License.

## Support

For issues and questions, please check the code comments and documentation within the files.

---

**Note**: This is a demonstration application. For production use, implement proper security measures, database integration, and WebSocket signaling.
