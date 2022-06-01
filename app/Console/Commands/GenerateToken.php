<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Sanctum\NewAccessToken;

class GenerateToken extends Command
{
    protected $signature = 'larabbs:generate-token';

    protected $description = '快速为用户生成 token';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->ask('输入用户 id');

        $user = User::find($userId);

        if (!$user) {
            return $this->error('用户不存在');
        }

        $token = $user->createToken('api')->plainTextToken;

        $this->info($token);
    }
}
