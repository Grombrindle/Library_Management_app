<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Resource::factory()->count(10)->create();

        foreach (Resource::all() as $resource) {

            $numRatings = rand(2, 4);
            $users = User::inRandomOrder()->take($numRatings)->get();

            foreach ($users as $user) {
                $rating = rand(1, 5); // This will give us whole numbers or half numbers
                DB::table('resources_ratings')->insert([
                    'user_id' => $user->id,
                    'resource_id' => $resource->id,
                    'rating' => $rating,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        //
    }
}
