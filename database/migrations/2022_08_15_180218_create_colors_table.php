<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function setInitialValues(): void
    {
        $defaultNames = [
            ['name' => 'Dorado', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Blanco', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Negro', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Plata', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sin color', 'created_at' => now(), 'updated_at' => now()],
        ];

        \App\Models\Color::query()->insert($defaultNames);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colors');
    }
};
