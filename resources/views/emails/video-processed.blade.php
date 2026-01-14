<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Processing Notification</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
        }
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .video-info {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .video-info h2 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #333;
        }
        .video-info p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .action-button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .action-button:hover {
            transform: translateY(-2px);
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .icon-success {
            color: #28a745;
        }
        .icon-error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon {{ $status ? 'icon-success' : 'icon-error' }}">
                @if($status)
                    ✓
                @else
                    ✗
                @endif
            </div>
            <h1>Video Processing {{ $status ? 'Complete' : 'Failed' }}</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <span class="status-badge {{ $status ? 'status-success' : 'status-error' }}">
                {{ $status ? 'SUCCESS' : 'FAILED' }}
            </span>

            <div class="video-info">
                <h2>{{ $videoTitle }}</h2>
                <p><strong>Status:</strong> {{ $message }}</p>
                @if($status)
                    <p><strong>Location:</strong> {{ $videoUrl }}</p>
                @endif
            </div>

            @if($status)
                <p>Your video has been successfully processed and is now ready to view.</p>
                <a href="{{ url($videoUrl) }}" class="action-button">View Video</a>
            @else
                <p>We encountered an issue while processing your video. Please check the error details above and try again.</p>
                <a href="{{ url('/upload') }}" class="action-button">Upload Again</a>
            @endif

            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                This is an automated notification from your Content Management System.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>You received this email because you are registered to receive video processing notifications.</p>
        </div>
    </div>
</body>
</html>
