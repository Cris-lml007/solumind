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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_delivery_id_foreign');
            $table->dropIndex('transactions_delivery_id_foreign');

            $table->foreign('delivery_id')->references('id')->on('deliveries')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_delivery_id_foreign');
            $table->dropIndex('transactions_delivery_id_foreign');

            $table->foreign('delivery_id')->references('id')->on('deliveries')->nullOnDelete();
        });
    }
};
