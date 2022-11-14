<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Create Admin
         User::factory()->createOne([
            'first_name' => 'Technify',
            'email' => 'admin@technify.com',
            'password' => bcrypt('Technify1234'),
            'is_admin' => true,
            'last_name' => 'Admin'
        ]);
    }
};
