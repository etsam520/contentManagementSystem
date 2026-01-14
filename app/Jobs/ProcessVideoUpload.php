<?php

namespace App\Jobs;

use App\Services\MqttService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        Log::info("Finished processing video: {$this->title}");
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
