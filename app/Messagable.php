<?php

namespace App;

use App\Models\Messaging\Attachment;
use App\Models\Messaging\Message;
use Illuminate\Support\Facades\Storage;

trait Messagable
{
    /**
     * Send a message to one or more users.
     *
     * @param array|int $recipients
     * @param string $subject
     * @param string $body
     * @param UploadedFile[] $attachments
     * @param bool $isSystem
     * @return Message
     */
    public function sendMessage(array|int $recipients, string $subject, string $body, array $file_attachments = [], array $uploaded_attachments = [], bool $isSystem = false): Message
    {
        $message = Message::create([
            'subject' => $subject,
            'body' => $body,
        ]);

        $message->setSender($isSystem ? null : $this->id);

        $recipients = is_array($recipients) ? $recipients : [$recipients];
        $message->addRecipients($recipients);

        if (!empty($file_attachments)) {
            $message->addAttachments($file_attachments);
        }

        if (!empty($uploaded_attachments)) {
            $message->linkAttachments($uploaded_attachments);
        }

        return $message;
    }

    /**
     * Send a message from the system to this user.
     *
     * @param string $subject
     * @param string $body
     * @param UploadedFile[] $attachments
     * @return Message
     */
    public function receiveSystemMessage(string $subject, string $body, array $attachments = []): Message
    {
        return $this->sendMessage(recipients: $this->id, subject: $subject, body: $body, uploaded_attachments: $attachments, isSystem: true);
    }

    public function calculateUsedAttachmentStorage(): int
{
    $userId = $this->id;

    // Get all message IDs where user is either sender or recipient
    $messageIds = Message::where('sender_id', $userId)
        ->orWhereHas('recipients', function ($q) use ($userId) {
            $q->where('recipient_id', $userId);
        })
        ->pluck('id');

    // Get all attachments from those messages
    $attachments = Attachment::whereIn('message_id', $messageIds)->get();

    // Sum the size of each file
    $totalSize = 0;
    foreach ($attachments as $attachment) {
        if (Storage::exists($attachment->file_path)) {
            $totalSize += Storage::size($attachment->file_path);
        }
    }

    return $totalSize; // in bytes
}
}
