<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $user = User::create([
            'name' => 'Amitav Roy',
            'email' => 'reachme@amitavroy.com',
            'password' => bcrypt('Password@123'),
            'email_verified_at' => now(),
        ]);

        $user->videos()->create([
            'url' => 'https://youtu.be/lnZJuXmWG3o',
            'title' => 'Next JS Registration flow completed with Formik and Yup validation',
            'description' => 'In this video, we are going to look at the complete code for the registration of the user.',
            'is_published' => 1,
        ]);

        $user->videos()->create([
            'url' => 'https://youtu.be/lnZJuXmWG3o',
            'title' => 'Next JS Handling email verification token on server side in Next Js to check token',
            'description' => 'In this video, I am going to show you the code behind verifying the token that a user will get on his/her email.',
            'is_published' => 1,
        ]);

        Course::create([
            'name' => 'My Laravel course',
            'description' => 'This is my first Laravel course.',
            'user_id' => 1,
        ]);
    }
}
