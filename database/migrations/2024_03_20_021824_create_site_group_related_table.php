<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteGroupRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_group', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('company_code', 30);
            $table->string('status', 10)->default('ACTIVE');
            $table->timestamps();
            $table->softdeletes();
            $table->index('name');
        });

        Schema::create('site_group_admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_group_id');
            $table->unsignedBigInteger('site_profile_id');
            $table->string('username', 20);
            $table->string('password', 255)->nullable();
            $table->string('name', 255);
            $table->boolean('is_master_account')->default(false);
            $table->string('status', 10)->default('ACTIVE');
            $table->dateTime('last_login_at')->nullable();
            $table->timestamps();
            $table->softdeletes();

            $table->unique(['username', 'deleted_at'], 'unique_username');
            $table->index('site_group_id');
            $table->index('username');
            $table->index('status');
        });

        Schema::create('site_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_group_id');
            $table->string('name', 255);
            $table->boolean('is_superadmin')->default(false);
            $table->timestamps();
            $table->softdeletes();

            $table->index('name');
            $table->index('site_group_id');
        });

        Schema::create('site_profile_module', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_profile_id');
            $table->unsignedBigInteger('module_id');
        });

        Schema::create('site_group_admin_site', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_group_admin_id');
            $table->unsignedBigInteger('site_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_group');
        Schema::dropIfExists('site_group_admin');
        Schema::dropIfExists('site_profile');
        Schema::dropIfExists('site_profile_module');
        Schema::dropIfExists('site_group_admin_site');
    }
}
