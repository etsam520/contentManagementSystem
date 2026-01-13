<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title',
        'filename',
        'path',
        'size',
        'mime_type',
        'duration'
    ];

    protected $casts = [
        'size' => 'integer',
        'duration' => 'integer',
    ];
}
