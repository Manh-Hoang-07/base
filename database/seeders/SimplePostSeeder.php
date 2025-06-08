<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimplePostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user or create one
        $user = User::first();
        if (!$user) {
            echo "No users found. Please create a user first.\n";
            return;
        }

        echo "Creating 10,000 simple posts...\n";
        
        $posts = [];
        $batchSize = 1000;
        
        for ($i = 1; $i <= 10000; $i++) {
            $posts[] = [
                'user_id' => $user->id,
                'name' => "Post Title {$i}",
                'description' => "This is a simple description for post number {$i}. It contains basic information about the post content.",
                'content' => "This is the content for post number {$i}. It contains detailed information about the topic being discussed in this post. The content is designed to be informative and engaging for readers.",
                'image' => ($i % 3 === 0) ? 'images/logo_sm.png' : null,
                'require_login' => ($i % 5 === 0) ? 1 : 0,
                'status' => ($i % 10 === 0) ? 'inactive' : 'active',
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
        
        echo "Successfully created 10,000 simple posts!\n";
    }
}
