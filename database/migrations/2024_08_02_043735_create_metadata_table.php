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
        Schema::create('metadata', function (Blueprint $table) {
            $table->increments('metadata_id');
            $table->integer('parent_id')->nullable();
            $table->string('metadata_type');
            $table->string('metadata_label');
            $table->string('metadata_value');
            $table->dateTime('metadata_createdAt')->nullable();
            $table->dateTime('metadata_updatedAt')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadata');
    }
};
