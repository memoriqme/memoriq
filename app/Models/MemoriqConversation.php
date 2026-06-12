<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoriqConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payload_version',
        'encrypted_header',
        'encrypted_body',
        'body_storage_disk',
        'body_storage_path',
        'body_bytes',
    ];

    protected $casts = [
        'payload_version' => 'integer',
        'body_bytes' => 'integer',
    ];
}
