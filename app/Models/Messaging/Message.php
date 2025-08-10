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
    public $incrementing = false;
    protected $fillable = ['subject', 'body','sender_id'];

    protected $hidden = [
        'body',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'messaging_recipients', 'message_id', 'recipient_id')
            ->withPivot('read_at')->withTimestamps();
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
        $this->sender_id = $senderId;
    }
}
