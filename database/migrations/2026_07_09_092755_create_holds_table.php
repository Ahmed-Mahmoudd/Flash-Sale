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
        Schema::create('holds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('qty');

            $table->enum('status', [
                'active',
                'consumed',
                'expired',
                'released'
            ])->default('active');

            $table->timestamp('expires_at');

            $table->timestamps(); // created_at and updated_at columns

            $table->index(['status', 'expires_at']); // for efficient querying of active holds
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holds');
    }
};
