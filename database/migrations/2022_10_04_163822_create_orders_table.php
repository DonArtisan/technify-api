<?php

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->decimal('amount');
            $table->decimal('tax');
            $table->decimal('total');
            $table->date('required_date');
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $enumName = 'orders_status_enum';
            $allowed = array_map(fn (string $value) => "'{$value}'", \App\Enums\OrderStatus::getValues());

            DB::statement("DROP TYPE IF EXISTS {$enumName}");
            DB::statement("CREATE TYPE {$enumName} AS ENUM (".implode(', ', $allowed).')');
            DB::statement("ALTER TABLE orders ADD order_status $enumName NOT NULL");
        });

        Schema::table('orders', function (Blueprint $table) {
            $enumName = 'authorize_enum';
            $allowed = array_map(fn (string $value) => "'{$value}'", \App\Enums\AuthorizeEnum::getValues());

            DB::statement("DROP TYPE IF EXISTS {$enumName}");
            DB::statement("CREATE TYPE {$enumName} AS ENUM (".implode(', ', $allowed).')');
            DB::statement("ALTER TABLE orders ADD authorize_status $enumName NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
