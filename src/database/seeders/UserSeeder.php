<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;
use App\Models\Transaction;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //  Очистка таблиц перед сидированием
        // Transaction::truncate();
        // User::truncate();
        User::query()->delete();
        $password = 'password';
        // Создание администратора
        $admin = User::create([
            'name' => 'Admin_User',
            'email' => 'admin@example.com',
            'password' => bcrypt($password),
        ]);
        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        // Создание оператора
        $operator = User::create([
            'name' => 'Operator User',
            'email' => 'operator@example.com',
            'password' => bcrypt($password),
        ]);
        $operatorRole = Role::where('name', 'oper')->first();
        $operator->roles()->attach($operatorRole);

        // Создание обычного пользователя
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt($password),
        ]);
        $userRole = Role::where('name', 'user')->first();
        $user->roles()->attach($userRole);
    }
}
