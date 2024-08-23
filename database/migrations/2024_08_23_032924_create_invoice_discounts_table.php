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
        Schema::create('invoice_discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->string('discount_type')->nullable();
            $table->integer('amount')->default(0);

            $table->string('invoice_discount_type')->nullable();
            $table->string('invoice_discount_id')->nullable();
            $table->timestamps();
            
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_discounts');
    }
};
