<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
             'name' => 'Paul Hager',
             'email' => 'paul.hager888@gmail.com',
             'password' => bcrypt('password'),
         ])->assignRole('admin');
    }
}
