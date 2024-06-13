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
        Schema::create('portfolio', function (Blueprint $table) {
            $table->id();
            $table->bigInt('user_id')->index();
            $table->bigInt('crypto_id')->nullable();
            $table->string('crypto_name', 255);
            $table->int('unit')->default(0); 
            $table->int('buy_price')->default(0);
            $table->timestamps();
        });

        Schema::create('watchlist', function (Blueprint $table) {
            $table->id();
            $table->bigInt('user_id')->index();
            $table->bigInt('crypto_id')->nullable();
            $table->string('crypto_name', 255);
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
        Schema::dropIfExists('portfolio_related_tables');
    }
};
