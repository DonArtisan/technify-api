<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Customer;
use App\Models\Person;
use App\Models\ProductSale;
use App\Models\Seller;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([ProductSeeder::class]);
        Seller::factory()->count(10)->create();
        Supplier::factory()->count(20)->create();
        ProductSale::factory()->count(5)->create();
        Customer::factory()->count(10)->create();
    }
}
