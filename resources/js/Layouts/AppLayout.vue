<script setup>
import { onMounted, ref } from 'vue'
import NotificationToast from '@/Components/NotificationToast.vue'

const notificationRef = ref(null)

onMounted(() => {
    // Make notification function globally available
    if (notificationRef.value) {
        window.showNotification = notificationRef.value.addNotification
    }
})
</script>

<template>
    <div>
        <slot />
        <NotificationToast ref="notificationRef" />
    </div>
</template>
