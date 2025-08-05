<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Models\Messaging\Message;
use App\Models\Messaging\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MessagingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'attachments.*' => 'file|max:10240',
        ]);

        DB::transaction(function () use ($request) {
            /** @var \App\Models\User $user */
            $user  = auth()->user();
            $user->sendMessage(
                recipients: $request->recipients,
                subject: $request->subject,
                body: $request->body,
                file_attachments: $request->attachments
            );
        });

        return $this->sendResponse(__('Message sent successfully.'));
    }

    
    public function show(Message $message)
    {
        Recipient::where('message_id',$message->id)
            ->where('recipient_id' , auth()->id())
            ->update(["read_at" => Carbon::now()]);
        return $this->sendResponse(__('Fetched Successfully'), $message->load("recipients","sender", "attachments"));
    }
}
