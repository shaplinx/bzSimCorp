<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Models\Messaging\Message;
use App\Models\Messaging\Recipient;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class MessageController extends Controller
{
    public function index(IndexRequest $request)
    {   
        Gate::authorize('viewAny', Message::class);
        $request->validate([
            "messageType" => "required|string|in:inbox,outbox"
        ]);

        /** @var \App\Models\User $user */
        $user  = Auth::user();


        $data =  Message::with('recipients', 'sender')
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            })
            ->when($request->messageType === "outbox", function ($q) use ($user) {
                $q->where('sender_id', $user->id);
            })
            ->when($request->messageType === "inbox", function ($q) use ($user) {
                $q->whereHas('recipients', function ($q) use ($user) {
                    $q->where('recipient_id', $user->id);
                });
            })
            ->paginate($request->pageSize ?? 10);

        return $this->sendResponseWithPaginatedData($data);
    }


    public function store(Request $request)
    {
        Gate::authorize('create', Message::class);

        $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'attachments' => 'array|max:5',
            'attachments.*' => 'file|max:5120',
        ]);

        $message = DB::transaction(function () use ($request) {
            /** @var \App\Models\User $user */


            $user  = Auth::user();

            $message =  Message::create([
                'sender_id' => $user->id,
                'subject' => $request->subject,
                'body' => $request->body,
            ]);

            $message->addRecipients($request->recipients);
            $message->addAttachments($request->attachments);
            return $message;
        });

        return $this->sendResponse(__('Message sent successfully.'), $message);
    }


    public function show(Message $message)
    {   
        Gate::authorize('view', $message);
        Recipient::where('message_id', $message->id)
            ->where('recipient_id', Auth::id())
            ->update(["read_at" => Carbon::now()]);
        return $this->sendResponse(__('Fetched Successfully'), $message->makeVisible('body')->load("recipients", "sender", "attachments"));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        Gate::authorize('delete', $message);

        $message = DB::transaction(function () use ($message) {
            $message->attachments->each(fn($a) => $a->delete());
            $message->delete();
        });

        return $this->sendResponse(__("Deleted Successfully"));
    }
}
