<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    videos: {
        type: Array,
        default: () => []
    }
})

const selectedVideo = ref(null)
const showVideoModal = ref(false)
const showDeleteModal = ref(false)
const videoToDelete = ref(null)
const isDeleting = ref(false)

function playVideo(video) {
    selectedVideo.value = video
    showVideoModal.value = true
}

function closeModal() {
    showVideoModal.value = false
    selectedVideo.value = null
}

function confirmDelete(video, event) {
    event.stopPropagation()
    videoToDelete.value = video
    showDeleteModal.value = true
}

function closeDeleteModal() {
    showDeleteModal.value = false
    videoToDelete.value = null
}

function deleteVideo() {
    if (!videoToDelete.value) return
    
    isDeleting.value = true
    
    router.delete(route('videos.destroy', videoToDelete.value.id), {
        onSuccess: () => {
            closeDeleteModal()
            isDeleting.value = false
        },
        onError: () => {
            isDeleting.value = false
        }
    })
}
</script>

<template>
    <AppLayout>
        <Head title="Video Library" />
    
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-violet-900 py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <Link :href="route('home')" class="inline-flex items-center text-white hover:text-gray-300 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Home
                </Link>
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold text-white mb-2">Video Library</h1>
                        <p class="text-gray-300">{{ videos.length }} video(s) available</p>
                    </div>
                    <Link
                        :href="route('upload.page')"
                        class="bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105"
                    >
                        Upload New Video
                    </Link>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="videos.length === 0" class="text-center py-20">
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-12 border border-white/20">
                    <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-white mb-2">No videos yet</h2>
                    <p class="text-gray-300 mb-6">Start by uploading your first video</p>
                    <Link
                        :href="route('upload.page')"
                        class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold py-3 px-8 rounded-lg hover:from-blue-600 hover:to-purple-700"
                    >
                        Upload Video
                    </Link>
                </div>
            </div>

            <!-- Video Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="video in videos"
                    :key="video.id"
                    class="bg-white/10 backdrop-blur-lg rounded-xl overflow-hidden border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl cursor-pointer"
                    @click="playVideo(video)"
                >
                    <!-- Video Thumbnail -->
                    <div class="relative aspect-video bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                        </svg>
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                            </svg>
                        </div>
                        
                        <!-- Delete Button -->
                        <button
                            @click="confirmDelete(video, $event)"
                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-all duration-200 transform hover:scale-110 z-10"
                            title="Delete video"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Video Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-white mb-2 truncate">
                            {{ video.title }}
                        </h3>
                        <div class="flex items-center justify-between text-sm text-gray-300">
                            <span>{{ video.size_formatted }}</span>
                            <span>{{ video.created_at }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 truncate">
                            {{ video.filename }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Player Modal -->
        <div
            v-if="showVideoModal && selectedVideo"
            class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4"
            @click="closeModal"
        >
            <div class="max-w-5xl w-full" @click.stop>
                <!-- Modal Header -->
                <div class="bg-white/10 backdrop-blur-lg rounded-t-2xl p-4 border border-white/20 border-b-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-white">{{ selectedVideo.title }}</h2>
                        <button
                            @click="closeModal"
                            class="text-white hover:text-gray-300 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Video Player -->
                <div class="bg-black rounded-b-2xl overflow-hidden">
                    <video
                        :src="selectedVideo.url"
                        controls
                        autoplay
                        class="w-full"
                        controlsList="nodownload"
                    >
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- Video Details -->
                <div class="bg-white/10 backdrop-blur-lg rounded-b-2xl p-4 border border-white/20 border-t-0 mt-1">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">Size:</span>
                            <span class="text-white ml-2">{{ selectedVideo.size_formatted }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Uploaded:</span>
                            <span class="text-white ml-2">{{ selectedVideo.created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal && videoToDelete"
            class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4"
            @click="closeDeleteModal"
        >
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 max-w-md w-full" @click.stop>
                <div class="text-center">
                    <!-- Warning Icon -->
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-500/20 mb-4">
                        <svg class="h-10 w-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-white mb-2">Delete Video?</h3>
                    <p class="text-gray-300 mb-2">
                        Are you sure you want to delete "{{ videoToDelete.title }}"?
                    </p>
                    <p class="text-sm text-gray-400 mb-6">
                        This action cannot be undone. The video file will be permanently deleted.
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <button
                            @click="closeDeleteModal"
                            :disabled="isDeleting"
                            class="flex-1 px-6 py-3 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
                        >
                            Cancel
                        </button>
                        <button
                            @click="deleteVideo"
                            :disabled="isDeleting"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
                        >
                            <span v-if="!isDeleting">Delete</span>
                            <span v-else>Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
