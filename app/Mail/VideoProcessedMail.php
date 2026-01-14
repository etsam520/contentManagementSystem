<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VideoProcessedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $videoTitle;
    public string $videoUrl;
    public bool $status;
    public string $message;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $videoTitle,
        string $videoUrl,
        bool $status = true,
        string $message = 'Video processed successfully'
    ) {
        $this->videoTitle = $videoTitle;
        $this->videoUrl = $videoUrl;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->status
            ? "Video Processed Successfully: {$this->videoTitle}"
            : "Video Processing Failed: {$this->videoTitle}";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.video-processed',
            with: [
                'videoTitle' => $this->videoTitle,
                'videoUrl' => $this->videoUrl,
                'status' => $this->status,
                'message' => $this->message,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
