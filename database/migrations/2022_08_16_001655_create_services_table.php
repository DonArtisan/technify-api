<?php

use App\Enums\ServiceStatus;
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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_per_unit');
            $table->decimal('tax_percentage');
            $table->timestamps();
        });

        Schema::table('services', function (Blueprint $table) {
            $enumName = 'services_status_enum';
            $allowed = array_map(fn (string $value) => "'{$value}'", ServiceStatus::getValues());

            DB::statement("DROP TYPE IF EXISTS {$enumName}");
            DB::statement("CREATE TYPE {$enumName} AS ENUM (".implode(', ', $allowed).')');
            DB::statement("ALTER TABLE services ADD status $enumName NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
};
