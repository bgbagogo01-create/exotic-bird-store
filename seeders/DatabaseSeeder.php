<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin'
        ]);

        $kasirRole = Role::create([
            'name' => 'kasir',
            'display_name' => 'Kasir'
        ]);

        $pembeliRole = Role::create([
            'name' => 'pembeli',
            'display_name' => 'Pembeli'
        ]);

        // 2. Seed Default Users
        User::create([
            'role_id' => $adminRole->id,
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'role_id' => $kasirRole->id,
            'name' => 'Kasir Staff',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('kasir123'),
        ]);

        User::create([
            'role_id' => $pembeliRole->id,
            'name' => 'Pembeli Demo',
            'email' => 'pembeli@gmail.com',
            'password' => Hash::make('pembeli123'),
        ]);

        // 3. Seed Default Settings
        Setting::set('shop_name', 'EXOTIC BIRD STORE');
        Setting::set('shop_address', 'Jl. Jenderal Sudirman No. 100, Jakarta, Indonesia');
        Setting::set('shop_phone', '6281234567890');
        Setting::set('shop_email', 'admin@exoticbirdstore.com');
        Setting::set('shop_currency', 'Rp');
    }
}
