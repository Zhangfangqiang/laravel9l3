<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        return $user->isAuthorOf($reply);
    }

    public function destroy(User $user, Reply $reply)
    {
        #评论是他写的  ||  这篇文章的作者是他
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
