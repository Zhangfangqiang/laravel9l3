<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();

        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'admin';
        $user->email = 'zf18600004319@aliyun.com';
        $user->save();
    }
}
