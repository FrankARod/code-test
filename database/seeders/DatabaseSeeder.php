<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        User::factory()->subscribed()->create(['email' => 'active@codetest.test']);
        User::factory()->expired()->create(['email' => 'expired@codetest.test']);
        User::factory()->create(['email' => 'nosub@codetest.test']);
        User::factory()->subscribed()->count(3)->create();
    }
}
