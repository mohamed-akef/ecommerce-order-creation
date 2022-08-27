<?php

use App\Models\OrderProduct;
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
        Schema::create('order_product_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderProduct::class, 'order_product_id');
            $table->integer(column:'quantity', unsigned:true);
            $table->enum('unit', ['kg','g']);
            $table->enum('status', ['reserved', 'used', 'spoiled', 'reverted']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product_ingredients');
    }
};
