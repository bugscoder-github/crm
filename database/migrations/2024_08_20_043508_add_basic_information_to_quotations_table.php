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
            $table->string('company')->after('discount_code')->nullable();
            $table->string('premise_type')->after('company')->nullable();
            $table->string('customer_name')->after('premise_type')->nullable();
            $table->string('phone')->after('customer_name')->nullable();
            $table->string('email')->after('phone')->nullable();
            $table->text('delivery_address')->after('email')->nullable();
            $table->text('billing_address')->after('delivery_address')->nullable();
            $table->boolean('is_same_billing_address')->after('billing_address')->default(true);

            $table->dropColumn('is_shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->dropColumn('premise_type');
            $table->dropColumn('customer_name');
            $table->dropColumn('phone');
            $table->dropColumn('email');
            $table->dropColumn('delivery_address');
            $table->dropColumn('billing_address');
            $table->dropColumn('is_same_billing_address');

            $table->boolean('is_shipping_address')->after('discount_code')->default(false);
        });
    }
};
