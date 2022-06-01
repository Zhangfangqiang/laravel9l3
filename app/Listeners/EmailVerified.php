<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailVerified
{
    public function handle(Verified $event)
    {
        //这里可以写邮箱通过验证后调用的方法
    }
}
