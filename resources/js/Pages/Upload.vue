<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import axios from 'axios'

const CHUNK_SIZE = 2 * 1024 * 1024 // 2MB chunks
const selectedFile = ref(null)
const videoTitle = ref('')
const isUploading = ref(false)
const uploadProgress = ref(0)
const currentChunk = ref(0)
const totalChunks = ref(0)
const uploadId = ref(null)
const errorMessage = ref('')
const successMessage = ref('')

const progressPercentage = computed(() => {
    if (totalChunks.value === 0) return 0
    return Math.round((currentChunk.value / totalChunks.value) * 100)
})

const fileSizeFormatted = computed(() => {
    if (!selectedFile.value) return ''
    return formatBytes(selectedFile.value.size)
})

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const dm = decimals < 0 ? 0 : decimals
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i]
}

function handleFileSelect(event) {
    const file = event.target.files[0]
    if (file) {
        // Check if it's a video file
        if (!file.type.startsWith('video/')) {
            errorMessage.value = 'Please select a valid video file'
            return
        }
        selectedFile.value = file
        if (!videoTitle.value) {
            videoTitle.value = file.name.replace(/\.[^/.]+$/, '') // Remove extension
        }
        errorMessage.value = ''
        successMessage.value = ''
    }
}

async function startUpload() {
    if (!selectedFile.value) {
        errorMessage.value = 'Please select a video file'
        return
    }
    
    if (!videoTitle.value.trim()) {
        errorMessage.value = 'Please enter a video title'
        return
    }

    isUploading.value = true
    errorMessage.value = ''
    successMessage.value = ''
    currentChunk.value = 0
    totalChunks.value = Math.ceil(selectedFile.value.size / CHUNK_SIZE)
    
    try {
        // Step 1: Initialize upload
        const initResponse = await axios.post('/upload/init', {
            filename: selectedFile.value.name,
            total_chunks: totalChunks.value,
            file_size: selectedFile.value.size,
        })
        
        uploadId.value = initResponse.data.upload_id
        
        // Step 2: Upload chunks
        for (let i = 0; i < totalChunks.value; i++) {
            const start = i * CHUNK_SIZE
            const end = Math.min(start + CHUNK_SIZE, selectedFile.value.size)
            const chunk = selectedFile.value.slice(start, end)
            
            const formData = new FormData()
            formData.append('upload_id', uploadId.value)
            formData.append('chunk', chunk)
            formData.append('chunk_index', i)
            formData.append('total_chunks', totalChunks.value)
            
            await axios.post('/upload/chunk', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
            
            currentChunk.value = i + 1
            uploadProgress.value = progressPercentage.value
        }
        
        // Step 3: Complete upload
        const completeResponse = await axios.post('/upload/complete', {
            upload_id: uploadId.value,
            filename: selectedFile.value.name,
            total_chunks: totalChunks.value,
            file_size: selectedFile.value.size,
            title: videoTitle.value,
        })
        
        successMessage.value = 'Video uploaded successfully!'
        
        // Reset form
        setTimeout(() => {
            router.visit('/home')
        }, 2000)
        
    } catch (error) {
        console.error('Upload error:', error)
        errorMessage.value = error.response?.data?.message || 'Upload failed. Please try again.'
    } finally {
        isUploading.value = false
    }
}

function cancelUpload() {
    router.visit('/home')
}
</script>

<template>
    <AppLayout>
        <Head title="Upload Video" />
    
    <div class="min-h-screen bg-gray-900 py-12 px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-8 text-center">
                <Link :href="route('home')" class="inline-flex items-center text-white hover:text-gray-300 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Home
                </Link>
                <h1 class="text-4xl font-bold text-white mb-2">Upload Video</h1>
                <p class="text-gray-300">Upload your video with automatic chunked transfer</p>
            </div>

            <!-- Upload Card -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 shadow-2xl">
                <!-- Title Input -->
                <div class="mb-6">
                    <label class="block text-white text-sm font-semibold mb-2">
                        Video Title
                    </label>
                    <input
                        v-model="videoTitle"
                        type="text"
                        placeholder="Enter video title..."
                        :disabled="isUploading"
                        class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50"
                    />
                </div>

                <!-- File Input -->
                <div class="mb-6">
                    <label class="block text-white text-sm font-semibold mb-2">
                        Select Video File
                    </label>
                    <div class="relative">
                        <input
                            type="file"
                            accept="video/*"
                            @change="handleFileSelect"
                            :disabled="isUploading"
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 file:cursor-pointer disabled:opacity-50"
                        />
                    </div>
                    <p v-if="selectedFile" class="mt-2 text-sm text-gray-300">
                        Selected: {{ selectedFile.name }} ({{ fileSizeFormatted }})
                    </p>
                </div>

                <!-- Progress Section -->
                <div v-if="isUploading" class="mb-6">
                    <div class="flex justify-between text-white text-sm mb-2">
                        <span>Uploading...</span>
                        <span>{{ progressPercentage }}%</span>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-4 overflow-hidden">
                        <div
                            class="bg-gray-400 h-full rounded-full transition-all duration-300 flex items-center justify-center"
                            :style="{ width: progressPercentage + '%' }"
                        >
                            <span class="text-xs text-white font-semibold">{{ progressPercentage }}%</span>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-300">
                        Chunk {{ currentChunk }} of {{ totalChunks }}
                    </p>
                </div>

                <!-- Error Message -->
                <div v-if="errorMessage" class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-lg">
                    <p class="text-red-200">{{ errorMessage }}</p>
                </div>

                <!-- Success Message -->
                <div v-if="successMessage" class="mb-6 p-4 bg-green-500/20 border border-green-500/50 rounded-lg">
                    <p class="text-green-200">{{ successMessage }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <button
                        @click="startUpload"
                        :disabled="!selectedFile || !videoTitle.trim() || isUploading"
                        class="flex-1 bg-gray-900 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105"
                    >
                        <span v-if="!isUploading">Start Upload</span>
                        <span v-else>Uploading...</span>
                    </button>
                    
                    <button
                        @click="cancelUpload"
                        :disabled="isUploading"
                        class="px-6 py-3 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
                    >
                        Cancel
                    </button>
                </div>

                <!-- Info -->
                <div class="mt-6 p-4 bg-blue-500/20 border border-blue-500/30 rounded-lg">
                    <p class="text-sm text-blue-200">
                        <strong>Note:</strong> Videos are uploaded in 2MB chunks for reliable transfer of large files.
                        You'll see real-time progress as each chunk is uploaded.
                    </p>
                </div>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
