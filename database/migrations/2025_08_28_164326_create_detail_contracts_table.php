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
            $table->unsignedBigInteger('detailable_id');
            $table->string('detailable_type');
            $table->string('description');
            $table->integer('quantity');
            $table->decimal('purchase_price');
            $table->decimal('sale_price');
            $table->decimal('bill');
            $table->decimal('interest');
            $table->decimal('operating');
            $table->decimal('comission');
            $table->decimal('bank');
            $table->decimal('unexpected');
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
