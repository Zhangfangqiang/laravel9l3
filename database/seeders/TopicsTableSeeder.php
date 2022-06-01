<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
class TopicsTableSeeder extends Seeder
{

    use WithoutModelEvents; #使用这个可以在数据填充的过程中跳过 observers 观察员

    public function run()
    {
        Topic::factory()->count(100)->create();
    }
}

