<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('key')->default(Uuid::uuid4());
            $table->string('cod')->unique();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
