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
        Schema::create('detail_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('detailable_id')->nullable();
            $table->string('detailable_type')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity');
            $table->decimal('purchase_price');
            $table->decimal('sale_price');
            $table->decimal('bill');
            $table->decimal('interest');
            $table->decimal('operating');
            $table->decimal('comission');
            $table->decimal('bank');
            $table->decimal('unexpected');
            $table->decimal('purchase_total');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_contracts');
    }
};
