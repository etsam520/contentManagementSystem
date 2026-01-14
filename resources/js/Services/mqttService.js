import { Client } from 'paho-mqtt'

class MQTTService {
    constructor() {
        this.client = null
        this.isConnected = false
        this.reconnectAttempts = 0
        this.maxReconnectAttempts = 10
        this.messageCallbacks = []
    }

    connect() {
        const clientId = 'video_web_client_' + Math.random().toString(16).substr(2, 8)
        
        // Create MQTT client
        this.client = new Client(
            import.meta.env.VITE_MQTT_CLIENT_HOST || 'mqtt.givni.in',
            Number(import.meta.env.VITE_MQTT_CLIENT_PORT) || 8884,
            clientId
        )

        // Set callbacks
        this.client.onConnectionLost = this.onConnectionLost.bind(this)
        this.client.onMessageArrived = this.onMessageArrived.bind(this)

        // Connection options
        const options = {
            useSSL: true,
            userName: import.meta.env.VITE_MQTT_CLIENT_AUTH_USERNAME || 'givnimqtt_client',
            password: import.meta.env.VITE_MQTT_CLIENT_AUTH_PASSWORD || '123456',
            onSuccess: this.onConnect.bind(this),
            onFailure: this.onFailure.bind(this),
            keepAliveInterval: 360,
            cleanSession: true,
            reconnect: true
        }

        // Connect
        try {
            this.client.connect(options)
            console.log('MQTT: Connecting to broker...')
        } catch (error) {
            console.error('MQTT: Connection error:', error)
        }
    }

    onConnect() {
        console.log('MQTT: Connected successfully')
        this.isConnected = true
        this.reconnectAttempts = 0

        // Subscribe to video processed topic
        this.subscribe('video/processed', 0)
    }

    onFailure(error) {
        console.error('MQTT: Connection failed:', error)
        this.isConnected = false

        // Attempt reconnection
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++
            console.log(`MQTT: Reconnecting... Attempt ${this.reconnectAttempts}/${this.maxReconnectAttempts}`)
            setTimeout(() => this.connect(), 5000)
        }
    }

    onConnectionLost(responseObject) {
        this.isConnected = false
        if (responseObject.errorCode !== 0) {
            console.log('MQTT: Connection lost:', responseObject.errorMessage)
            // Attempt reconnection
            if (this.reconnectAttempts < this.maxReconnectAttempts) {
                this.reconnectAttempts++
                setTimeout(() => this.connect(), 5000)
            }
        }
    }

    onMessageArrived(message) {
        console.log('MQTT: Message received on topic:', message.destinationName)
        console.log('MQTT: Payload:', message.payloadString)

        try {
            const payload = JSON.parse(message.payloadString)
            
            // Call all registered callbacks
            this.messageCallbacks.forEach(callback => {
                callback({
                    topic: message.destinationName,
                    payload: payload
                })
            })
        } catch (error) {
            console.error('MQTT: Error parsing message:', error)
        }
    }

    subscribe(topic, qos = 0) {
        if (this.client && this.isConnected) {
            this.client.subscribe(topic, {
                qos: qos,
                onSuccess: () => console.log(`MQTT: Subscribed to ${topic}`),
                onFailure: (error) => console.error(`MQTT: Failed to subscribe to ${topic}:`, error)
            })
        } else {
            console.warn('MQTT: Cannot subscribe, client not connected')
        }
    }

    onMessage(callback) {
        this.messageCallbacks.push(callback)
    }

    disconnect() {
        if (this.client && this.isConnected) {
            this.client.disconnect()
            console.log('MQTT: Disconnected')
            this.isConnected = false
        }
    }
}

// Create singleton instance
const mqttService = new MQTTService()

export default mqttService
