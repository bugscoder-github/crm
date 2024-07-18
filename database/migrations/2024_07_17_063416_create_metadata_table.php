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
            $table->id('metadata_id');
            $table->integer('parent_id')->nullable();
            $table->string('metadata_type');
            $table->string('metadata_label');
            $table->string('metadata_value');
            $table->timestamp('metadata_createdAt')->nullable();
            $table->timestamp('metadata_updatedAt')->nullable();
            $table->softDeletes();
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
