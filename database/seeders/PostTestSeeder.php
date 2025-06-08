<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Series;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Get all users and series for random assignment
        $userIds = User::pluck('id')->toArray();
        $seriesIds = Series::pluck('id')->toArray();

        // If no users exist, create some
        if (empty($userIds)) {
            $userIds = [1]; // Assume admin user exists
        }

        // If no series exist, create some
        if (empty($seriesIds)) {
            $seriesIds = [null]; // Allow null series
        } else {
            $seriesIds[] = null; // Add null option for posts without series
        }

        $posts = [];
        $batchSize = 100; // Smaller batch for testing

        echo "Creating 1,000 test posts...\n";

        for ($i = 1; $i <= 1000; $i++) {
            $posts[] = [
                'name' => $faker->sentence(rand(3, 8)),
                'slug' => $faker->unique()->slug(rand(3, 6)),
                'description' => $faker->text(150), // Limit to 150 chars
                'content' => $faker->paragraphs(rand(3, 8), true),
                'image' => $faker->optional(0.5)->randomElement([
                    'images/logo_sm.png',
                    'images/logo.png',
                    'images/favicon.ico',
                    null
                ]),
                'meta_title' => $faker->optional(0.7)->sentence(rand(5, 10)),
                'meta_description' => $faker->optional(0.7)->paragraph(1),
                'meta_keywords' => $faker->optional(0.5)->words(rand(3, 8), true),
                'status' => $faker->randomElement([0, 1]),
                'require_login' => $faker->randomElement([0, 1]),
                'views' => $faker->numberBetween(0, 5000),
                'likes' => $faker->numberBetween(0, 500),
                'user_id' => $faker->randomElement($userIds),
                'series_id' => $faker->randomElement($seriesIds),
                'published_at' => $faker->optional(0.8)->dateTimeBetween('-1 year', 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert in batches
            if ($i % $batchSize === 0) {
                DB::table('posts')->insert($posts);
                $posts = [];
                echo "Inserted {$i} posts...\n";
            }
        }

        // Insert remaining posts
        if (!empty($posts)) {
            DB::table('posts')->insert($posts);
        }

        echo "Successfully created 1,000 test posts!\n";
    }
}
