<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotentialClientsTable extends Migration
{
    public function up()
    {
        Schema::create('potential_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('city');
            $table->string('payment_method');
            $table->string('plan_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('potential_clients');
    }
}
