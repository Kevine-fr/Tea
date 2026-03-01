<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('ticket_code_id');
            $table->uuid('prize_id')->nullable();
            $table->timestamp('participation_date')->useCurrent();

            // Un code ticket = une seule participation
            $table->unique('ticket_code_id');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->foreign('ticket_code_id')
                  ->references('id')
                  ->on('ticket_codes')
                  ->restrictOnDelete();

            $table->foreign('prize_id')
                  ->references('id')
                  ->on('prizes')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participations');
    }
};