<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Video;
use Inertia\Inertia;

class UploadController extends Controller
{
    public function init(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'total_chunks' => 'required|integer',
            'file_size' => 'required|integer',
        ]);

        // Generate unique identifier for this upload session
        $uploadId = Str::uuid()->toString();
        
        // Create temporary directory for chunks
        $chunkDir = storage_path("app/chunks/{$uploadId}");
        if (!file_exists($chunkDir)) {
            mkdir($chunkDir, 0755, true);
        }

        return response()->json([
            'upload_id' => $uploadId,
            'message' => 'Upload initialized successfully'
        ]);
    }

    public function uploadChunk(Request $request)
    {
        $request->validate([
            'upload_id' => 'required|string',
            'chunk' => 'required|file',
            'chunk_index' => 'required|integer',
            'total_chunks' => 'required|integer',
        ]);

        $uploadId = $request->upload_id;
        $chunkIndex = $request->chunk_index;
        $totalChunks = $request->total_chunks;
        
        // Save chunk
        $chunkDir = storage_path("app/chunks/{$uploadId}");
        $chunkPath = "{$chunkDir}/chunk_{$chunkIndex}";
        
        $request->file('chunk')->move($chunkDir, "chunk_{$chunkIndex}");

        return response()->json([
            'message' => 'Chunk uploaded successfully',
            'chunk_index' => $chunkIndex,
            'total_chunks' => $totalChunks
        ]);
    }

    public function complete(Request $request)
    {
        $request->validate([
            'upload_id' => 'required|string',
            'filename' => 'required|string',
            'total_chunks' => 'required|integer',
            'file_size' => 'required|integer',
            'title' => 'required|string',
        ]);

        $uploadId = $request->upload_id;
        $filename = $request->filename;
        $totalChunks = $request->total_chunks;
        $title = $request->title;
        
        $chunkDir = storage_path("app/chunks/{$uploadId}");
        
        // Merge all chunks
        $finalFilename = time() . '_' . Str::slug(pathinfo($filename, PATHINFO_FILENAME)) . '.' . pathinfo($filename, PATHINFO_EXTENSION);
        $finalPath = storage_path("app/public/videos/{$finalFilename}");
        
        // Create videos directory if it doesn't exist
        $videosDir = storage_path("app/public/videos");
        if (!file_exists($videosDir)) {
            mkdir($videosDir, 0755, true);
        }
        
        // Merge chunks
        $finalFile = fopen($finalPath, 'wb');
        
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = "{$chunkDir}/chunk_{$i}";
            if (file_exists($chunkPath)) {
                $chunk = fopen($chunkPath, 'rb');
                stream_copy_to_stream($chunk, $finalFile);
                fclose($chunk);
                unlink($chunkPath); // Delete chunk after merging
            }
        }
        
        fclose($finalFile);
        
        // Remove chunk directory
        rmdir($chunkDir);
        
        // Get file info
        $fileSize = filesize($finalPath);
        $mimeType = mime_content_type($finalPath);
        
        // Save video record to database
        $video = Video::create([
            'title' => $title,
            'filename' => $finalFilename,
            'path' => "videos/{$finalFilename}",
            'size' => $fileSize,
            'mime_type' => $mimeType,
        ]);

        return response()->json([
            'message' => 'Video uploaded successfully',
            'video' => $video
        ]);
    }

    public function index()
    {
        $videos = Video::latest()->get()->map(function ($video) {
            return [
                'id' => $video->id,
                'title' => $video->title,
                'filename' => $video->filename,
                'size' => $video->size,
                'size_formatted' => $this->formatBytes($video->size),
                'url' => asset('storage/' . $video->path),
                'created_at' => $video->created_at->diffForHumans(),
            ];
        });

        return Inertia::render('VideoList', [
            'videos' => $videos
        ]);
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        
        // Delete the video file from storage
        $filePath = storage_path('app/public/' . $video->path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete the database record
        $video->delete();
        
        return redirect()->route('videos.index')->with('success', 'Video deleted successfully');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
