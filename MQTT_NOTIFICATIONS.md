# MQTT Video Notification System

## 🎉 Implementation Complete!

Your application now has real-time MQTT notifications for video processing updates.

## 📋 Features Implemented

### 1. **MQTT Client Service**
- Auto-connects to MQTT broker on app load
- Subscribes to `video/processed` topic (QoS: 0)
- Auto-reconnection on connection loss (up to 10 attempts)
- Connection credentials from environment variables

### 2. **Real-Time Notifications**
- Beautiful toast notifications appear in top-right corner
- Auto-dismiss after 5 seconds
- Support for different types: success, error, info
- Smooth animations (slide in/out)
- Click to dismiss manually
- Optional "View Video" link

### 3. **Message Format**
The system listens for messages with this JSON format:
```json
{
    "title": "output",
    "message": "Video processed successfully",
    "url": "http://127.0.0.1:8000/storage/videos/1768323395_output.mp4",
    "status": true
}
```

## 🔧 Configuration

### Environment Variables (.env)
```env
VITE_MQTT_CLIENT_HOST=mqtt.givni.in
VITE_MQTT_CLIENT_PORT=8884
VITE_MQTT_CLIENT_AUTH_USERNAME=givnimqtt_client
VITE_MQTT_CLIENT_AUTH_PASSWORD=123456
VITE_MQTT_CLIENT_TLS_ENABLED=true
```

### MQTT Settings
- **Host:** mqtt.givni.in
- **Port:** 8884 (WebSocket with TLS)
- **Username:** givnimqtt_client
- **Password:** 123456
- **TLS:** Enabled
- **Topic:** video/processed
- **QoS:** 0

## 📁 Files Created/Modified

### New Files:
1. **resources/js/Services/mqttService.js** - MQTT client service
2. **resources/js/Components/NotificationToast.vue** - Toast notification component
3. **resources/js/Layouts/AppLayout.vue** - Layout wrapper with notifications

### Modified Files:
1. **resources/js/app.js** - Initialize MQTT and handle messages
2. **resources/js/Pages/Welcome.vue** - Added AppLayout wrapper
3. **resources/js/Pages/VideoList.vue** - Added AppLayout wrapper
4. **resources/js/Pages/Upload.vue** - Added AppLayout wrapper
5. **.env** - Added VITE_MQTT_* environment variables

## 🚀 How It Works

### Connection Flow:
1. App loads → MQTT service initializes
2. Connects to broker using credentials
3. Subscribes to `video/processed` topic
4. Listens for incoming messages

### Notification Flow:
1. Backend publishes message to `video/processed` topic
2. MQTT service receives message
3. Parses JSON payload
4. Calls `window.showNotification()` with data
5. Toast appears in top-right corner
6. Auto-dismisses after 5 seconds

## 🧪 Testing

### Test the Connection:
1. Open browser console (F12)
2. Visit http://localhost:8000
3. Look for these logs:
   ```
   MQTT: Connecting to broker...
   MQTT: Connected successfully
   MQTT: Subscribed to video/processed
   ```

### Test Notifications Manually:
Open browser console and run:
```javascript
window.showNotification({
    type: 'success',
    title: 'Test Notification',
    message: 'This is a test message',
    url: 'http://localhost:8000'
})
```

### Test with Real MQTT Message:
Publish this message to `video/processed` topic:
```json
{
    "title": "Video Ready",
    "message": "Your video has been processed successfully!",
    "url": "http://127.0.0.1:8000/storage/videos/sample.mp4",
    "status": true
}
```

## 🎨 Notification Types

### Success (Green)
```javascript
window.showNotification({
    type: 'success',
    title: 'Success!',
    message: 'Operation completed'
})
```

### Error (Red)
```javascript
window.showNotification({
    type: 'error',
    title: 'Error!',
    message: 'Something went wrong'
})
```

### Info (Blue)
```javascript
window.showNotification({
    type: 'info',
    title: 'Information',
    message: 'Here is some info'
})
```

### With Video Link
```javascript
window.showNotification({
    type: 'success',
    title: 'Video Ready',
    message: 'Click to view your processed video',
    url: 'http://localhost:8000/storage/videos/output.mp4'
})
```

## 🔍 Troubleshooting

### MQTT Connection Issues:
- Check browser console for error messages
- Verify environment variables are loaded (restart dev server after .env changes)
- Ensure port 8884 is accessible
- Check TLS/SSL certificate validity

### Notifications Not Showing:
- Check if `window.showNotification` function exists in console
- Verify AppLayout is wrapping your pages
- Check for JavaScript errors in console

### WebSocket Connection:
The client uses WebSocket protocol over TLS (wss://) on port 8884. Ensure your firewall allows this connection.

## 📱 Browser Compatibility
- ✅ Chrome/Edge (Chromium)
- ✅ Firefox
- ✅ Safari
- ✅ Opera

## 🎯 Next Steps

1. **Backend Integration:** Set up your backend to publish video processing results to the MQTT topic
2. **Custom Topics:** Add more topics for different notification types
3. **Persistence:** Store notifications in local storage for offline viewing
4. **Sound Alerts:** Add sound effects for important notifications
5. **User Preferences:** Allow users to enable/disable notifications

## 📝 Example Backend Publish (Python)

```python
import paho.mqtt.client as mqtt
import json
import ssl

client = mqtt.Client()
client.username_pw_set("givnimqtt", "dk123123")
client.tls_set(cert_reqs=ssl.CERT_NONE)
client.connect("mqtt.givni.in", 8883, 60)

message = {
    "title": "Video Processed",
    "message": "Your video is ready to view!",
    "url": "http://127.0.0.1:8000/storage/videos/output.mp4",
    "status": True
}

client.publish("video/processed", json.dumps(message), qos=0)
client.disconnect()
```

## 🌟 Features

- ✅ Real-time notifications via MQTT
- ✅ Beautiful animated toasts
- ✅ Auto-dismiss with manual close option
- ✅ Clickable video links
- ✅ Success/Error/Info states
- ✅ Secure TLS connection
- ✅ Auto-reconnection
- ✅ Multiple notifications support
- ✅ Responsive design

Your application is now fully equipped with real-time video processing notifications! 🎊
