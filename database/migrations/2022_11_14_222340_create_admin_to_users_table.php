<?php

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        /** @var Person $person */
        $person = Person::factory()->createOne([
            'email' => 'admin@technify.com',
        ]);

        // Create Admin
        /** @var User $user */
        $user = $person->user()->create([
            'email_verified_at' => now(),
            'password' => bcrypt('Technify1234'),
        ]);

        $user->assign('admin');
    }
};
