<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\User;
use App\Models\ResourceRating;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create resources using factory
        Resource::factory()->count(10)->create();

        // Create ratings for each resource
        foreach (Resource::all() as $resource) {
            if(rand(0,1)) {
                $resource->name = $resource->name." With Rev";
                $resource->save();

                $numRatings = rand(2, 4);
                $users = User::inRandomOrder()->take($numRatings)->get();

                foreach ($users as $user) {
                    $rating = rand(1, 5);

                    // Use the ResourceRating model instead of raw DB queries
                    ResourceRating::create([
                        'user_id' => $user->id,
                        'resource_id' => $resource->id,
                        'rating' => $rating,
                        'review' => fake()->optional(0.7)->sentence(),
                    ]);
                }
            }
            else {
                $resource->name = $resource->name." No Rev";
                $resource->save();
            }
        }
    }
}
