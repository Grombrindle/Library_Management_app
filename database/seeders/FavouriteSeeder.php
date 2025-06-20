<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Favourite;
use App\Models\User;
use App\Models\Teacher;

class FavouriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $teachers = Teacher::all();
        $combinations = [];

        if ($users->isEmpty() || $teachers->isEmpty()) {
            $this->command->info('Please seed users and teachers first.');
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $userId = $users->random()->id;
            $teacherId = $teachers->random()->id;
            $pair = $userId . '-' . $teacherId;

            if (!in_array($pair, $combinations)) {
                Favourite::factory()->create([
                    'user_id' => $userId,
                    'teacher_id' => $teacherId,
                ]);
                $combinations[] = $pair;
            }
        }
    }
} 