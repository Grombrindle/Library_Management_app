<?php

namespace Database\Seeders;

use App\Models\SavedMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class SavedMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for($i = 0; $i < 25; $i++) {
            SavedMessage::factory()->create([
                'text' => fake()->text(300),
                'user_id' => rand(1, User::count()),
                'date' => fake()->dateTime(),
            ]);
        }
    }
}
