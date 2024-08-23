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
        Schema::dropIfExists('invoices');

        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('quotation_id');
            $table->string('invoice_number');
            $table->timestamp('invoice_date');
            $table->string('discount_code')->nullable();
            $table->string('company')->nullable();
            $table->string('premise_type')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->boolean('is_same_billing_address')->default(true);
            $table->string('currency')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->integer('sub_total')->default(0);
            $table->integer('total_discount')->default(0);
            $table->integer('total_tax')->default(0);
            $table->integer('total_amount')->default(0);
            $table->string('status')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        
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
};
