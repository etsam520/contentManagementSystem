<?php

namespace App\Jobs;

use App\Services\MqttService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessVideoUpload implements ShouldQueue
{
    use Queueable , Dispatchable, InteractsWithQueue, SerializesModels;

    public string $path;
    public string $title;
    public string $url;

    public int $timeout = 120; // seconds
    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(string $path, string $title, string $url)
    {
        $this->path = $path;
        $this->title = $title;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Processing video: {$this->title}");
        sleep(5);
        /*
        * ======================// Simulate video processing logic //================
        
        $fileContents = file_get_contents($this->path);
        $remotePath = 'vidyarupenacademy/videos/' . preg_match('/[^\/]+$/', $this->path, $matches)[0];
        Storage::disk('spaces')->put($remotePath, $fileContents, 'public');
        Log::info("Finished processing video: {$this->title}");
         if (file_exists($this->path)) {
            unlink($this->path);
        } */
        $topic = "video/processed";
        $message = json_encode([
            'title' => $this->title,
            'message' => 'Video processed successfully',
            'url' => $this->url,
            'status' => true
        ]);
        $qos = 0;
        $mqttService = new MqttService();
        $success = $mqttService->publish($topic, $message, $qos);
    }

    public function failed(\Throwable $exception): void
    {
        $topic = "video/processed";
        $message = json_encode([
            'title' => $this->title,
            'message' => 'Video processing failed',
            'url' => $this->url,
            'status' => false
        ]);
        $qos = 0;
        $mqttService = new MqttService();
        $success = $mqttService->publish($topic, $message, $qos);

        Log::error('Video processing failed', [
            'title' => $this->title,
            'error' => $exception->getMessage(),
        ]);
    }
}
