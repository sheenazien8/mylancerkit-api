<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //App\Models\User::truncate();
        factory(App\Models\User::class, 10)->create();
        $users = App\Models\User::get();
        foreach ($users as $user) {
            $profile = new App\Models\Profile();
            $profile->user()->associate($user);
            $profile->save();
        }
    }
}
