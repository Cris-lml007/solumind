<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '12345'
        ]);

        $a = new Account();
        $a->name = 'Cuentas por Pagar';
        $a->id = 1;
        $a->save();

        $b = new Account();
        $b->name = 'Cuentas por Cobrar';
        $b->id = 2;
        $b->save();
    }
}
