<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class UserTableSeeder extends Seeder
{
    /**
     * User table seeder for inserting 10K records.
     *
     * @throws Exception
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => Uuid::generate(),
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'SuperAdmin',
            'email' => 'soninishant287@gmail.com',
            'password' => bcrypt('secret'),
            'address' => 'Nyati Tech Park, Pune India',
            'house_number' => '4',
            'postal_code' => '123456',
            'city' => 'Pune',
            'telephone_number' => '9999999999',
            'is_active' => 1
        ]);

        factory(\App\User::class, 1000)->create();
    }
}