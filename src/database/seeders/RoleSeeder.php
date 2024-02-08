<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  Очистка таблиц перед сидированием
        DB::table('role_user')->truncate();
        // Role::truncate();
        Role::query()->delete();

        $this->command->info('Starting RoleSeeder');

        Role::create(['name' => 'admin','descr'=>'Администратор']);
        Role::create(['name' => 'oper','descr'=>'Оператор']);
        Role::create(['name' => 'user','descr'=>'Пользователь']);

        $this->command->info('RoleSeeder completed');
    }
}
