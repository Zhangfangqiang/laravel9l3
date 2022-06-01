<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UsersTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(TopicsTableSeeder::class);
        $this->call(RepliesTableSeeder::class);
        $this->call(RolesAndPermissionsTableSeeder::class);
        $this->call(LinksTableSeeder::class);

        Model::reguard();
    }
}
