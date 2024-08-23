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
        Schema::dropIfExists('invoice_items');

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('item_type')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('unit_amount')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->integer('sub_total')->default(0);
            $table->integer('line_amount')->default(0);
            $table->timestamps();
            
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('invoiceItem_id');
            $table->integer('invoice_id');
            $table->string('invoiceItem_desc');
            $table->integer('invoiceItem_ppu');
            $table->integer('invoiceItem_qty');
            $table->integer('invoiceItem_total');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }
};
