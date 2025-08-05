<?php

namespace App\Models\Messaging;

use App\Messagable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Http\UploadedFile;

class Message extends Model
{
    use HasFactory, HasUuids, Messagable;
    protected $table = "messaging_messages";

    protected $fillable = ['subject', 'body'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'message_recipients', 'message_id', 'recipient_id')
            ->withPivot('read_at')->withTimestamps();
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function uploadAttachments(array $files): array
    {
        return collect($files)
            ->filter(fn($file) => $file instanceof UploadedFile)
            ->map(function (UploadedFile $file) {
                return [
                    'file_path'     => $file->store('message_attachments'),
                    'original_name' => $file->getClientOriginalName(),
                ];
            })
            ->values()
            ->all();
    }
    public function linkAttachments(array $attachmentData): void
    {
        if (empty($attachmentData)) return;

        $prepared = collect($attachmentData)->map(function ($item) {
            return [
                'file_path'     => $item['file_path'],
                'original_name' => $item['original_name'],
                'message_id'    => $this->id,
            ];
        });

        Attachment::insert($prepared->all());
    }

    public function addAttachments(array $files): void
    {
        $attachments = $this->uploadAttachments($files);
        $this->linkAttachments($attachments);
    }


    public function addRecipients(array $userIds): void
    {
        $now = now();
        $records = collect($userIds)->map(function ($userId) use ($now) {
            return [
                'message_id'   => $this->id,
                'recipient_id' => $userId,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        })->toArray();

        if (!empty($records)) {
            Recipient::insert($records);
        }
    }

    public function setSender(?int $senderId): void
    {
        $this->sender_id = $senderId; // null = system
    }
}
