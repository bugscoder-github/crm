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
        Schema::dropIfExists('quotations');

        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('lead_id');
            $table->string('quotation_type')->nullable();
            $table->string('frequency_type')->nullable();
            $table->integer('frequency')->default(0);
            $table->string('quotation_number');
            $table->timestamp('quotation_date');
            $table->string('discount_code')->nullable();
            $table->boolean('is_shipping_address')->default(false);
            $table->string('currency')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('tax_name')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('tax_charge_type')->nullable();
            $table->integer('tax_rate')->default(0);
            $table->integer('sub_total')->default(0);
            $table->integer('total_discount')->default(0);
            $table->integer('total_tax')->default(0);
            $table->integer('total_amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');

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
};
