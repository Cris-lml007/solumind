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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->nullableMorphs('accountable');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            //$table->decimal('amount',15,2)->nullable();
            $table->date('date');
            $table->string('received_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contract_id')->references('id')->on('contracts')->cascadeOnDelete();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->date('date');
            $table->unsignedBigInteger('contract_partner_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->string('description');
            $table->decimal('amount',15,2);
            $table->integer('type');
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('contract_id')->references('id')->on('contracts')->nullOnDelete();
            $table->foreign('contract_partner_id')->references('id')->on('contract_partners')->nullOnDelete();
            $table->foreign('account_id')->references('id')->on('accounts')->nullOnDelete();
            $table->foreign('delivery_id')->references('id')->on('deliveries')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('accounts');
    }
};
