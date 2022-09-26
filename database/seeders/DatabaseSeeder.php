<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'ganendra',
            'email' => 'ganendra@email.com',
            'password' => bcrypt('password'),
            'created_at' => today(),
            'updated_at' => today(),
        ]);

        Cart::create();

        Product::factory(12)->create();
    }
}
