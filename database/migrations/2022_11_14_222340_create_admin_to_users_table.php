<?php

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        /** @var Person $person */
        $person = Person::factory()->createOne();

        // Create Admin
        /** @var User $user */
         $user = $person->user()->create([
            'password' => bcrypt('Technify1234'),
        ]);

         $user->assign('admin');
    }
};
