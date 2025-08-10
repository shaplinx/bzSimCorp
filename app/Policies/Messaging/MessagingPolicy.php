<?php

namespace App\Policies\Messaging;

use App\Models\User;
use App\Models\Messaging\Message;
use Illuminate\Auth\Access\Response;


class MessagingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermission("message:read")
            ? Response::allow()
            : Response::deny('You have no permission to read messages.');
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Message $model): Response
    {
        if (!$user->hasPermission("message:read")) {
            return Response::deny('You have no permission to read messages.');
        }

        if (!($model->sender_id === $user->id || $model->recipients()->where("messaging_recipients.recipient_id", $user->id)->exists())) {
            return Response::deny('You have no buisness to read this messages.');
        }
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if (!$user->hasPermission("message:create")) {
            return Response::deny('You have no access to create new message.');
        }
        if ($user->SentMessagesCount > 10) {
            return Response::deny('You have sent more than 10 messages, please free up your memory first');
        }

        return Response::allow();
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Message $model): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }
        if (!$user->hasPermission("message:delete")) {
            return Response::deny('You have no permission to delete messages.');
        }
        if ($user->id !== $model->sender_id) {
            return Response::deny('You can not delete messages unless it was sent by you.');
        }

        return Response::allow();
    }
}
