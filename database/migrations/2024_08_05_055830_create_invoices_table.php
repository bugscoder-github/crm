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
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('invoice_id');
            $table->integer('quotation_id');
            $table->string('invoice_name');
            $table->string('invoice_phone');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->string('invoice_company')->nullable();
            $table->string('invoice_email')->nullable();
            $table->string('invoice_premiseType')->nullable();
            $table->string('invoice_billingAddress')->nullable();
            $table->string('invoice_deliveryAddress')->nullable();
            $table->string('invoice_remark')->nullable();
            $table->string('invoice_tnc')->nullable();
            $table->integer('invoice_total')->nullable()->default(0);
            $table->integer('invoice_sst')->nullable()->default(0);
            $table->integer('invoice_grandTotal')->nullable()->default(0);
            $table->integer('invoice_sstPct')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
