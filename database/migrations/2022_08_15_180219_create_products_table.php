<?php

use App\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->decimal('price_per_unit');
            $table->decimal('tax_percentage');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $enumName = 'products_status_enum';
            $allowed = array_map(fn (string $value) => "'{$value}'", ProductStatus::getValues());

            DB::statement("DROP TYPE IF EXISTS {$enumName}");
            DB::statement("CREATE TYPE {$enumName} AS ENUM (".implode(', ', $allowed).')');
            DB::statement("ALTER TABLE products ADD status $enumName NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
