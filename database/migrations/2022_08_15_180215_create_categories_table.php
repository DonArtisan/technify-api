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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $this->setInitialValues();
    }

    public function setInitialValues(): void
    {
        $defaultNames = [
            ['name' => 'Computadoras', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Redes y Wifi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sonido', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Impresoras', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bateria', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Telefonos', 'created_at' => now(), 'updated_at' => now()],
        ];

        \App\Models\Category::query()->insert($defaultNames);
    }

        /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
