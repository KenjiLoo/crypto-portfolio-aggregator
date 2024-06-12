<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_profile_id')->index();
            $table->string('username', 20);
            $table->string('password', 255)->nullable();
            $table->string('name', 255);
            $table->boolean('is_superadmin')->default(false);
            $table->string('status', 50)->default('ACTIVE')->index();
            $table->dateTime('last_login_at')->nullable();

            $table->timestamps();
            $table->unsignedBigInteger('updated_by')->default(0);
            $table->softdeletes();

            $table->unique(['username', 'deleted_at'], 'unique_username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
