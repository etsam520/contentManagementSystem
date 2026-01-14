<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <Head title="Video Upload System" />
    
    <div class="min-h-screen bg-gray-900 flex items-center justify-center">
        <div class="max-w-4xl mx-auto px-6 py-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-6xl font-bold text-white mb-4">
                    Video Management System
                </h1>
                <p class="text-xl text-gray-300">
                    Upload and manage your video content with ease
                </p>
            </div>

            <!-- Action Cards -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Upload Card -->
                <Link :href="route('upload.page')" class="group">
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white mb-3">Upload Video</h2>
                            <p class="text-gray-300">
                                Upload your videos with chunked upload support and real-time progress tracking
                            </p>
                        </div>
                    </div>
                </Link>

                <!-- Video List Card -->
                <Link :href="route('videos.index')" class="group">
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-pink-500 to-orange-600 rounded-full mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white mb-3">Video Library</h2>
                            <p class="text-gray-300">
                                Browse and manage all your uploaded videos in one place
                            </p>
                        </div>
                    </div>
                </Link>
            </div>

            <!-- Features -->
            <div class="hidden mt-16 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl mb-2">⚡</div>
                    <h3 class="text-white font-semibold mb-2">Fast Upload</h3>
                    <p class="text-gray-400 text-sm">Chunked upload for large files</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl mb-2">📊</div>
                    <h3 class="text-white font-semibold mb-2">Progress Tracking</h3>
                    <p class="text-gray-400 text-sm">Real-time upload progress</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl mb-2">🎬</div>
                    <h3 class="text-white font-semibold mb-2">Video Preview</h3>
                    <p class="text-gray-400 text-sm">Play videos directly in browser</p>
                </div>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
