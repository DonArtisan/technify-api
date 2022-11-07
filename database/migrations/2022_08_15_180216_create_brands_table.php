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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $this->setInitialValues();
    }

    public function setInitialValues(): void
    {
        $defaultNames = [
            ['name' => 'Toshiba', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'MSI', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'Apple', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'Microsoft', 'created_at' => now(), 'updated_at' => now() ],
            ['name' => 'Lenovo', 'created_at' => now(), 'updated_at' => now() ],
        ];

        \App\Models\Brand::query()->insert($defaultNames);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
};
