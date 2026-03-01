<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redemptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('participation_id')->unique(); // 1 participation = 1 redemption max
            $table->string('method', 50); // ex: 'store', 'mail', 'online'
            $table->string('status', 30)->default('pending'); // pending, approved, rejected, completed
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();

            $table->foreign('participation_id')
                  ->references('id')
                  ->on('participations')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redemptions');
    }
};