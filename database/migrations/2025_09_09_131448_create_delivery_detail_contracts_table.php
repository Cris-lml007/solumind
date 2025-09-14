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
        Schema::create('delivery_detail_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_contract_id');
            $table->unsignedBigInteger('delivery_id');
            $table->integer('quantity');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('detail_contract_id')->references('id')->on('detail_contracts')->cascadeOnDelete();
            $table->foreign('delivery_id')->references('id')->on('deliveries')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_detail_contracts');
    }
};
