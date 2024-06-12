<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();
            $table->string('module', 50)->index();
            $table->string('action', 100)->index();
            $table->string('url', 255);
            $table->string('status', 10)->nullable();
            $table->text('request')->nullable();
            $table->text('response')->nullable();
            $table->string('user_type', 20)->nullable();
            $table->string('user_id', 20)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('ip', 50)->index();

            $table->timestamps();

            $table->index(['module', 'action'], 'module_action');
            $table->index(['user_type', 'user_id'], 'user_type_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_log');
    }
}
