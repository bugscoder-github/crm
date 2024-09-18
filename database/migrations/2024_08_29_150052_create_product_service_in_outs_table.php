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
        Schema::create('product_service_in_outs', function (Blueprint $table) {
            $table->id();
            $table->integer('product_service_id');
            $table->string('type');
            $table->float('unitPrice');
            $table->integer('qty');
            $table->integer('supplier_id');
            $table->integer('location_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_service_in_outs');
    }
};
