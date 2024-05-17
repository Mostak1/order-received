<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('status');
            $table->timestamp('orders_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations. UPDATE `orders` SET `orders_time`=STR_TO_DATE('4/3/2024', '%m/%d/%Y') WHERE `status`='work order 5';
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
