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
        Schema::dropIfExists('quotation_items');
        
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quotation_id');
            $table->string('item_type')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('unit_amount')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->integer('sub_total')->default(0);
            $table->integer('line_amount')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->increments('quotationItem_id');
            $table->integer('quotation_id');
            $table->string('quotationItem_desc');
            $table->integer('quotationItem_ppu');
            $table->integer('quotationItem_qty');
            $table->integer('quotationItem_total');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }
};
