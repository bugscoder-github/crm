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
        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_name')->nullable()->index();
            $table->text('description');
            $table->string('subject_type')->nullable();
            $table->integer('subject_id')->nullable();
            $table->string('causer_type')->nullable();
            $table->integer('causer_id')->nullable();
            $table->text('properties')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('event')->nullable();
            $table->string('batch_uuid')->nullable();

            $table->index(['causer_type', 'causer_id'], 'causer');
            $table->index(['subject_type', 'subject_id'], 'subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
