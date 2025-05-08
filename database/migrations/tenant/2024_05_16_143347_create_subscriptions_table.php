<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('plan_type');
            $table->text('description');
            $table->decimal('monthly_price', 8, 2); // Adjust precision and scale as needed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
