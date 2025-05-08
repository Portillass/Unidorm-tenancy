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
            $table->unsignedBigInteger('touristspot_id');
            $table->string('name');
            $table->string('phone');
            $table->integer('quantity')->default('1');
            $table->decimal('total_price', 10, 2)->default('1');
            $table->string('order_type');
            $table->string('subscriptionType');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }        

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};