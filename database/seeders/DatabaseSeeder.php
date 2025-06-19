<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        DB::table('users')->insert(
            [
                'name'=>'admin',
                'email'=>'Admin@gmail.com',
                'password'=>Hash::make('123456789'),
                'bio'=>'Admin1',
                'role'=>'admin',
                'created_at'=>now(),
                'updated_at'=>now(),            
            ]
            );

        // $user = new User();
        // $user->name ='Admin';
        // $user->email='Admin@gmail.com';
        // $user->password=bcrypt('admin123456789');
        // $user->role='admin';
        // $user->save();
    }
}
