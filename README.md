# 📹 Video Management System with Chunked Upload & Real-Time MQTT Notifications

A modern web application built with Laravel 11 and Vue 3 that enables seamless video file uploads with chunked transfer, real-time progress tracking, and MQTT-based notifications for video processing updates.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![Vue](https://img.shields.io/badge/Vue-3.x-green.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)

---

## 📋 Table of Contents

- [Features](#features)
- [Screenshots](#screenshots)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [MQTT Integration](#mqtt-integration)
- [API Endpoints](#api-endpoints)
- [Project Structure](#project-structure)
- [License](#license)

---

## ✨ Features

### 🎯 Core Features
- **Chunked File Upload**: Upload large video files in 2MB chunks for reliable transfer
- **Real-Time Progress Tracking**: Visual progress bar showing upload percentage and chunk count
- **Multiple File Type Support**: Videos, images, audio (MP3), and ZIP files
- **Video Library**: Beautiful grid-based video gallery with preview and playback
- **MQTT Notifications**: Real-time toast notifications for video processing updates
- **Delete Functionality**: Remove uploaded videos with confirmation modal
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices

### 🎨 UI/UX Features
- Modern gradient-based dark theme
- Glass morphism effects (frosted glass look)
- Smooth animations and transitions
- Toast notifications with auto-dismiss
- Modal-based video player
- Hover effects and interactive elements

### 🔧 Technical Features
- **Secure File Storage**: Server-side file management with organized storage
- **Database Integration**: MySQL database for video metadata
- **Auto-Reconnection**: MQTT client automatically reconnects on connection loss
- **Error Handling**: Comprehensive error handling with user-friendly messages
- **Clean Architecture**: Separation of concerns with services and components

---

## 📸 Screenshots

### Landing Page
<!-- Add screenshot: screenshots/landing-page.png -->
![Landing Page](./screenshots/landing-page.png)
*Beautiful landing page with navigation to upload and video library*

### Video Upload Page
<!-- Add screenshot: screenshots/upload-page.png -->
![Upload Page](./screenshots/upload-page.png)
*Upload interface with file selection and title input*

### Upload Progress
<!-- Add screenshot: screenshots/upload-progress.png -->
![Upload Progress](./screenshots/upload-progress.png)
*Real-time progress tracking with chunk counter*

### Video Library
<!-- Add screenshot: screenshots/video-library.png -->
![Video Library](./screenshots/video-library.png)
*Grid-based video library with thumbnails and metadata*

### Video Player Modal
<!-- Add screenshot: screenshots/video-player.png -->
![Video Player](./screenshots/video-player.png)
*Full-featured video player in modal view*

### Delete Confirmation
<!-- Add screenshot: screenshots/delete-confirmation.png -->
![Delete Confirmation](./screenshots/delete-confirmation.png)
*Confirmation modal before deleting a video*

### MQTT Notification
<!-- Add screenshot: screenshots/notification-toast.png -->
![MQTT Notification](./screenshots/notification-toast.png)
*Real-time notification for video processing updates*

### Mobile View
<!-- Add screenshot: screenshots/mobile-view.png -->
![Mobile View](./screenshots/mobile-view.png)
*Responsive design on mobile devices*

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0+
- **Queue System**: Database-based queue

### Frontend
- **Framework**: Vue 3 (Composition API)
- **Routing**: Inertia.js
- **Build Tool**: Vite 6.x
- **Styling**: Tailwind CSS 3.x
- **HTTP Client**: Axios
- **MQTT Client**: Paho MQTT

### Real-Time Communication
- **Protocol**: MQTT over WebSocket with TLS
- **Broker**: Custom MQTT broker
- **Topics**: `video/processed`
- **QoS**: 0

---

## 🚀 Installation

### Prerequisites
```bash
- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM or Yarn
- MySQL >= 8.0
- Git
```

### Step 1: Clone Repository
```bash
git clone <your-repository-url>
cd contentManagementSystem
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install Node Dependencies
```bash
npm install --legacy-peer-deps
```

### Step 4: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### Step 5: Database Configuration
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=content_management_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 6: Run Migrations
```bash
php artisan migrate
```

### Step 7: Storage Setup
```bash
php artisan storage:link
```

### Step 8: Build Frontend Assets
```bash
npm run build
```

### Step 9: Start Development Server
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite Dev Server (optional for development)
npm run dev
```

### Step 10: Access Application
Open your browser and navigate to:
```
http://localhost:8000
```

---

## ⚙️ Configuration

### MQTT Configuration
Add these variables to your `.env` file:

```env
# MQTT Client Configuration (for frontend)
VITE_MQTT_CLIENT_HOST=mqtt.givni.in
VITE_MQTT_CLIENT_PORT=8884
VITE_MQTT_CLIENT_AUTH_USERNAME=givnimqtt_client
VITE_MQTT_CLIENT_AUTH_PASSWORD=123456
VITE_MQTT_CLIENT_TLS_ENABLED=true

# MQTT Server Configuration (for backend)
MQTT_HOST=mqtt.givni.in
MQTT_PORT=8883
MQTT_TLS_ENABLED=true
MQTT_CLIENT_ID=mqtt_client_server
MQTT_AUTH_USERNAME=givnimqtt
MQTT_AUTH_PASSWORD=dk123123
MQTT_ENABLE_LOGGING=true
```

### File Upload Configuration
Default chunk size is 2MB. To modify, edit:
```javascript
// resources/js/Pages/Upload.vue
const CHUNK_SIZE = 2 * 1024 * 1024 // 2MB
```

### Storage Configuration
Videos are stored in:
```
storage/app/public/videos/
```

Accessible via:
```
public/storage/videos/
```

---

## 📖 Usage

### 1. Uploading a Video

1. **Navigate to Home Page** (`http://localhost:8000`)
2. **Click "Upload Video"** button
3. **Enter Video Title** in the input field
4. **Select Video File** using the file picker
5. **Click "Start Upload"**
6. **Watch Progress** as chunks are uploaded
7. **Auto-redirect** to home page after completion

### 2. Viewing Video Library

1. **Navigate to Home Page**
2. **Click "Video Library"** button
3. **Browse Videos** in grid layout
4. **Click Video Card** to play in modal
5. **Use Video Controls** (play, pause, volume, fullscreen)

### 3. Deleting a Video

1. **Go to Video Library**
2. **Hover over Video Card**
3. **Click Red Delete Button** (trash icon)
4. **Confirm Deletion** in modal
5. **Video Removed** from library and storage

### 4. Receiving MQTT Notifications

Notifications appear automatically when:
- Video processing is complete
- Processing errors occur
- Any update is published to `video/processed` topic

**Notification includes:**
- Title and message
- Success/error indicator
- Optional link to view video
- Auto-dismiss after 5 seconds

---

## 🔌 MQTT Integration

### Connection Details
```javascript
Host: mqtt.givni.in
Port: 8884 (WebSocket + TLS)
Protocol: MQTT over WebSocket (wss://)
Username: givnimqtt_client
Password: 123456
Topic: video/processed
QoS: 0
```

### Message Format
The system expects JSON messages in this format:

```json
{
    "title": "Video Processing Complete",
    "message": "Your video has been processed successfully",
    "url": "http://127.0.0.1:8000/storage/videos/output.mp4",
    "status": true
}
```

**Fields:**
- `title` (string): Notification title
- `message` (string): Notification body text
- `url` (string, optional): Link to processed video
- `status` (boolean): Success (true) or error (false)

### Backend Publishing Example (Python)

```python
import paho.mqtt.client as mqtt
import json
import ssl

# Create client
client = mqtt.Client()
client.username_pw_set("givnimqtt", "dk123123")
client.tls_set(cert_reqs=ssl.CERT_NONE)

# Connect
client.connect("mqtt.givni.in", 8883, 60)

# Publish message
message = {
    "title": "Video Processed",
    "message": "Your video is ready!",
    "url": "http://127.0.0.1:8000/storage/videos/output.mp4",
    "status": True
}

client.publish("video/processed", json.dumps(message), qos=0)
client.disconnect()
```

### Testing MQTT in Browser Console

```javascript
// Test notification manually
window.showNotification({
    type: 'success',
    title: 'Test Notification',
    message: 'MQTT is working correctly!',
    url: 'http://localhost:8000'
});
```

---

## 🌐 API Endpoints

### Upload Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/upload/init` | Initialize upload session |
| POST | `/upload/chunk` | Upload single chunk |
| POST | `/upload/complete` | Finalize upload and merge chunks |

### Video Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/videos` | List all videos |
| DELETE | `/videos/{id}` | Delete specific video |

### Pages

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Landing page |
| GET | `/upload` | Upload page |
| GET | `/videos` | Video library |

---

## 📁 Project Structure

```
contentManagementSystem/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── UploadController.php      # Upload & video management
│   ├── Models/
│   │   └── Video.php                     # Video model
│   └── Services/
│       └── MqttService.php               # MQTT backend service
├── database/
│   └── migrations/
│       └── *_create_videos_table.php     # Video table schema
├── resources/
│   ├── js/
│   │   ├── Components/
│   │   │   └── NotificationToast.vue     # Toast notification component
│   │   ├── Layouts/
│   │   │   └── AppLayout.vue             # Main layout with notifications
│   │   ├── Pages/
│   │   │   ├── Welcome.vue               # Landing page
│   │   │   ├── Upload.vue                # Upload page
│   │   │   └── VideoList.vue             # Video library
│   │   ├── Services/
│   │   │   └── mqttService.js            # MQTT client service
│   │   └── app.js                        # Main application entry
│   └── views/
│       └── app.blade.php                 # Main blade template
├── routes/
│   └── web.php                           # Web routes
├── storage/
│   └── app/
│       ├── chunks/                       # Temporary chunk storage
│       └── public/
│           └── videos/                   # Final video storage
├── .env                                  # Environment configuration
├── composer.json                         # PHP dependencies
├── package.json                          # Node dependencies
├── vite.config.js                        # Vite configuration
└── README.md                             # This file
```

---

## 🔑 Key Components

### Backend Components

#### UploadController
Handles all upload operations:
- Initialize upload session
- Process chunk uploads
- Merge chunks into final file
- List and delete videos

#### Video Model
Database model for video metadata:
- Title, filename, path
- File size and MIME type
- Timestamps

#### MqttService (Backend)
Publishes processing updates to MQTT broker.

### Frontend Components

#### NotificationToast.vue
Toast notification component with:
- Multiple notification types (success, error, info)
- Auto-dismiss functionality
- Manual close option
- Smooth animations

#### AppLayout.vue
Main layout wrapper that:
- Includes notification component
- Exposes global notification function
- Wraps all pages

#### mqttService.js
MQTT client that:
- Connects to broker on app load
- Subscribes to topics
- Handles reconnection
- Parses and dispatches messages

---

## 🎨 Customization

### Change Theme Colors
Edit Tailwind colors in `tailwind.config.js`:

```javascript
theme: {
    extend: {
        colors: {
            primary: '#your-color',
            secondary: '#your-color',
        }
    }
}
```

### Modify Chunk Size
Edit in `resources/js/Pages/Upload.vue`:

```javascript
const CHUNK_SIZE = 5 * 1024 * 1024 // 5MB chunks
```

### Add New File Types
Update file validation in `Upload.vue`:

```javascript
const allowedTypes = [
    'video/', 'image/', 'audio/', 
    'application/zip', 'application/pdf'
];
```

---

## 🐛 Troubleshooting

### MQTT Connection Issues
1. Check console for connection errors
2. Verify `.env` variables are correct
3. Ensure port 8884 is accessible
4. Restart development server after `.env` changes

### Upload Failures
1. Check `storage/app/chunks/` permissions
2. Verify `storage/app/public/videos/` exists
3. Check PHP `upload_max_filesize` and `post_max_size`
4. Review Laravel logs in `storage/logs/`

### Build Errors
1. Clear cache: `npm run build && php artisan cache:clear`
2. Reinstall dependencies: `rm -rf node_modules && npm install --legacy-peer-deps`
3. Check for syntax errors in Vue files

---

## 📊 Performance

- **Chunk Size**: 2MB (configurable)
- **Concurrent Uploads**: 1 chunk at a time (sequential)
- **Max File Size**: Limited by server configuration
- **Storage**: Local filesystem (can be extended to S3/cloud)
- **Database**: MySQL with indexed columns

---

## 🔒 Security Features

- CSRF protection on all forms
- Secure file storage outside public directory
- File type validation
- MQTT authentication with username/password
- TLS/SSL encrypted MQTT connections
- SQL injection protection via Eloquent ORM

---

## 🚦 Browser Support

| Browser | Supported |
|---------|-----------|
| Chrome | ✅ Latest |
| Firefox | ✅ Latest |
| Safari | ✅ Latest |
| Edge | ✅ Latest |
| Opera | ✅ Latest |

---

## 📝 How to Take Screenshots

To add screenshots to this README:

1. **Create Screenshots Directory**:
   ```bash
   mkdir screenshots
   ```

2. **Take Screenshots**:
   - **Landing Page**: Visit `http://localhost:8000`
   - **Upload Page**: Click "Upload Video"
   - **Progress**: Start uploading a file
   - **Library**: Click "Video Library"
   - **Player**: Click on any video card
   - **Delete**: Hover and click delete button
   - **Notification**: Trigger MQTT message or use browser console:
     ```javascript
     window.showNotification({
         type: 'success',
         title: 'Test',
         message: 'Screenshot demo'
     })
     ```
   - **Mobile**: Use browser dev tools (F12) → Device toolbar

3. **Save Screenshots** to:
   ```
   screenshots/
   ├── landing-page.png
   ├── upload-page.png
   ├── upload-progress.png
   ├── video-library.png
   ├── video-player.png
   ├── delete-confirmation.png
   ├── notification-toast.png
   └── mobile-view.png
   ```

4. **Update README**: Images will automatically display once files are added

---

## 📄 License

This project is licensed under the MIT License.

---

## 🙏 Acknowledgments

- Laravel Team for the amazing framework
- Vue.js Team for the progressive framework
- Tailwind CSS for the utility-first CSS framework
- Paho MQTT for the JavaScript client
- Inertia.js for seamless SPA experience

---

## 🗺️ Roadmap

- [ ] Add support for more file types (PDF, documents)
- [ ] Implement video transcoding
- [ ] Add thumbnail generation
- [ ] User authentication and authorization
- [ ] Video sharing and permissions
- [ ] Advanced search and filtering
- [ ] Batch upload support
- [ ] Cloud storage integration (AWS S3)
- [ ] Video analytics and statistics
- [ ] Mobile app (React Native)

---

## 📈 Changelog

### Version 1.0.0 (January 2026)
- Initial release
- Chunked video upload
- MQTT notifications
- Video library with player
- Delete functionality
- Responsive design

---

**Made with ❤️ using Laravel and Vue.js**
