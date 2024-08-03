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
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('quotation_id');
            $table->integer('lead_id');
            $table->string('quotation_name');
            $table->string('quotation_phone');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->string('quotation_company')->nullable();
            $table->string('quotation_email')->nullable();
            $table->string('quotation_premiseType')->nullable();
            $table->string('quotation_billingAddress')->nullable();
            $table->string('quotation_deliveryAddress')->nullable();
            $table->string('quotation_remark')->nullable();
            $table->string('quotation_tnc')->nullable();
            $table->integer('quotation_total')->nullable()->default(0);
            $table->integer('quotation_sst')->nullable()->default(0);
            $table->integer('quotation_grandTotal')->nullable()->default(0);
            $table->integer('quotation_sstPct')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
