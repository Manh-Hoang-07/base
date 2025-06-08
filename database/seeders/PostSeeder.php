<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN'); // Sử dụng locale Việt Nam

        // Get all users for random assignment
        $userIds = User::pluck('id')->toArray();

        // If no users exist, create some
        if (empty($userIds)) {
            $userIds = [1]; // Assume admin user exists
        }

        $posts = [];
        $batchSize = 1000; // Insert in batches for better performance

        echo "Creating 10,000 posts...\n";

        for ($i = 1; $i <= 10000; $i++) {
            $posts[] = [
                'user_id' => $faker->randomElement($userIds),
                'name' => $faker->sentence(rand(3, 6)), // Shorter title
                'description' => $faker->text(150), // Limit description to 150 chars to be safe
                'content' => $faker->paragraphs(rand(3, 8), true), // Shorter content
                'image' => $faker->optional(0.7)->randomElement([
                    'images/logo_sm.png',
                    'images/logo.png',
                    'images/favicon.ico',
                    null
                ]),
                'require_login' => $faker->randomElement([0, 1]), // 0: public, 1: require login
                'status' => $faker->randomElement(['active', 'inactive']), // Use enum values
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

        echo "Successfully created 10,000 posts!\n";
    }
}
