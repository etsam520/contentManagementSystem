<script setup>
import { ref, computed, onMounted } from 'vue'

const notifications = ref([])
let notificationId = 0

const addNotification = (notification) => {
    const id = notificationId++
    notifications.value.push({
        id,
        ...notification,
        show: false
    })

    // Trigger animation
    setTimeout(() => {
        const notif = notifications.value.find(n => n.id === id)
        if (notif) notif.show = true
    }, 10)

    // Auto remove after 5 seconds
    setTimeout(() => {
        removeNotification(id)
    }, 5000)
}

const removeNotification = (id) => {
    const notif = notifications.value.find(n => n.id === id)
    if (notif) {
        notif.show = false
        setTimeout(() => {
            notifications.value = notifications.value.filter(n => n.id !== id)
        }, 300)
    }
}

const getIcon = (type) => {
    switch (type) {
        case 'success':
            return `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`
        case 'error':
            return `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`
        case 'info':
            return `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`
        default:
            return `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>`
    }
}

const getColorClasses = (type) => {
    switch (type) {
        case 'success':
            return 'bg-green-500/20 border-green-500/50 text-green-100'
        case 'error':
            return 'bg-red-500/20 border-red-500/50 text-red-100'
        case 'info':
            return 'bg-blue-500/20 border-blue-500/50 text-blue-100'
        default:
            return 'bg-purple-500/20 border-purple-500/50 text-purple-100'
    }
}

// Expose method globally
if (typeof window !== 'undefined') {
    window.showNotification = addNotification
}

defineExpose({ addNotification })
</script>

<template>
    <div class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 max-w-md">
        <transition-group name="notification">
            <div
                v-for="notification in notifications"
                :key="notification.id"
                :class="[
                    'backdrop-blur-lg rounded-xl p-4 border shadow-2xl transition-all duration-300 transform',
                    getColorClasses(notification.type || 'success'),
                    notification.show ? 'translate-x-0 opacity-100' : 'translate-x-full opacity-0'
                ]"
            >
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0" v-html="getIcon(notification.type || 'success')"></div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-white mb-1">{{ notification.title }}</h4>
                        <p class="text-sm opacity-90">{{ notification.message }}</p>
                        <a
                            v-if="notification.url"
                            :href="notification.url"
                            target="_blank"
                            class="text-xs mt-2 inline-flex items-center hover:underline"
                        >
                            View Video
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                    <button
                        @click="removeNotification(notification.id)"
                        class="flex-shrink-0 text-white hover:opacity-70 transition-opacity"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </transition-group>
    </div>
</template>

<style scoped>
.notification-enter-active,
.notification-leave-active {
    transition: all 0.3s ease;
}

.notification-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.notification-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
</style>
