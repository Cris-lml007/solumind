<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use App\Models\UserPermission;
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

        $u = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '12345'
        ]);

        $u->is_active = true;
        $u->save();

        UserPermission::create([
            'user_id' => $u->id,
            'product' => 4,
            'supplier' => 4,
            'partner' => 4,
            'item' => 4,
            'transaction' => 4,
            'delivery' => 4,
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
