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
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('lead_id');
            $table->string('lead_phone');
            $table->string('lead_name')->nullable();
            $table->dateTime('lead_createdAt')->nullable();
            $table->dateTime('lead_updatedAt')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('fkcustomer_id')->nullable()->default(0);
            $table->integer('lead_isOldCustomer')->nullable()->default(0);
            $table->integer('user_id')->nullable()->default(0);
            $table->string('lead_location')->nullable()->default('');
            $table->string('lead_enquiry')->nullable()->default('');
            $table->string('lead_source')->nullable()->default('');
            $table->dateTime('read_at')->nullable();
            $table->integer('leadComment_count')->nullable()->default(0);
            $table->dateTime('done_at')->nullable();
            $table->integer('quotation_id')->nullable()->default(0);
            $table->string('lead_companyName')->nullable()->default('');
            $table->string('lead_premiseType')->nullable()->default('');
            $table->string('lead_email')->nullable()->default('');
            $table->string('lead_remark')->nullable()->default('');
            $table->string('lead_serviceType')->nullable()->default('EMPTY');
            $table->integer('lead_createdBy')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
