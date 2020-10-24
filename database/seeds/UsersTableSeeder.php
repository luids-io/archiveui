<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(config('admin.admin_name')) {
            User::firstOrCreate(
                ['username' => config('admin.admin_username')], [
                    'name' => config('admin.admin_name'),
                    'email' => config('admin.admin_email'),
                    'password' => bcrypt(config('admin.admin_password')),
                ]
            );
        }
    }
}
