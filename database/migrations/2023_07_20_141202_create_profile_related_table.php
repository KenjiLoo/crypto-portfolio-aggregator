<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id')->nullable()->index();
            $table->string('type', 10)->default('admin')->index();
            $table->string('modulekey', 20);
            $table->string('name', 255);
            $table->unsignedTinyInteger('sequence')->default(0);
            $table->string('icon', 255)->nullable();
            $table->string('route', 255)->nullable();
            $table->boolean('is_superadmin')->default(false);
            $table->boolean('is_master')->default(false);
            $table->boolean('is_hidden')->default(false);

            $table->timestamps();
            $table->unsignedBigInteger('updated_by')->default(0);
            $table->softDeletes();

            $table->unique(['type', 'modulekey', 'deleted_at'], 'unique_type_modulekey');
        });

        Schema::create('admin_module', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('module_id');
            $table->string('status', 20)->default('add');
        });

        Schema::create('admin_profile', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->boolean('is_superadmin')->default(false);

            $table->timestamps();
            $table->unsignedBigInteger('updated_by')->default(0);
            $table->softDeletes();
        });

        Schema::create('admin_profile_module', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_profile_id');
            $table->unsignedBigInteger('module_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module');
        Schema::dropIfExists('admin_module');
        Schema::dropIfExists('admin_profile');
        Schema::dropIfExists('admin_profile_module');
    }
}
