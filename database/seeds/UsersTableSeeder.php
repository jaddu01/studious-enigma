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
        DB::table('users')->insert([
            'access_level_id' => 0,
            'first_name' => 'admin',
            'last_name' => '',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
