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
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('tax_name');
            $table->dropColumn('tax_type');
            $table->dropColumn('tax_charge_type');
            $table->dropColumn('tax_rate');

            $table->timestamp('deleted_at')->after('updated_at')->nullable();
        });

        Schema::create('quotation_taxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quotation_id');
            $table->string('tax_name')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('tax_charge_type')->nullable();
            $table->integer('tax_rate')->default(0);
            $table->integer('tax_amount')->default(0);
            $table->timestamps();

            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_taxes');

        Schema::table('quotations', function (Blueprint $table) {
            $table->string('tax_name')->after('currency_symbol')->nullable();
            $table->string('tax_type')->after('tax_name')->nullable();
            $table->string('tax_charge_type')->after('tax_type')->nullable();
            $table->integer('tax_rate')->after('tax_charge_type')->default(0);

            $table->dropColumn('deleted_at');
        });
    }
};
