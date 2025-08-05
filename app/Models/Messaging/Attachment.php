<?php

namespace App\Models\Messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory, HasUuids;
    protected $table = "messaging_attachments";
    protected $fillable = ['message_id', 'file_path', 'original_name'];

        protected static function booted()
    {
        static::deleting(function ($attachment) {
            if ($attachment->file_path && Storage::exists($attachment->file_path)) {
                Storage::delete($attachment->file_path);
            }
        });
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
