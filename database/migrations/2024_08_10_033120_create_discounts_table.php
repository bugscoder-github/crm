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
        Schema::create('discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id');
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->string('discount_apply_type');
            $table->string('discount_type');
            $table->integer('amount')->default(0);
            $table->integer('minimum_amount')->default(0);

            $table->boolean('is_redemption')->default(false);
            $table->integer('quantity')->default(0)->nullable();

            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
