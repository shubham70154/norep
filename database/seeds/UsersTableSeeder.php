<?php

use App\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [[
            'id'             => 1,
            'name'           => 'Admin',
            'email'          => 'admin@admin.com',
            'password'       => bcrypt('1234'),
            'remember_token' => null,
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
            'deleted_at'     => null,
        ],
        [
            'id'             => 2,
            'name'           => 'Shubham Gupta',
            'email'          => 'shubham@admin.com',
            'password'       => bcrypt('1234'),
            'remember_token' => null,
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
            'deleted_at'     => null,
        ],
        [
            'id'             => 3,
            'name'           => 'Sam',
            'email'          => 'sam@admin.com',
            'password'       => bcrypt('1234'),
            'remember_token' => null,
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
            'deleted_at'     => null,
        ]
    ];

        User::insert($users);
    }
}
