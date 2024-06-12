<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_group_id');
            $table->string('name', 255);
            $table->string('site_code', 50);
            $table->string('company_code', 30);
            $table->string('currency', 5);
            $table->decimal('usd_rate', 16, 6)->default(0.000000);
            $table->string('status', 10)->default('ACTIVE');
            $table->timestamps();
            $table->softdeletes();

            $table->unique(['site_code', 'deleted_at'], 'unique_sitecode');
            $table->index('site_group_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site');
    }
}
