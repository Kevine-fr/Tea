<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_auths', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->smallInteger('provider_id');
            $table->string('provider_user_id'); // ID côté provider OAuth
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['provider_id', 'provider_user_id']);

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->foreign('provider_id')
                  ->references('id')
                  ->on('auth_providers')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_auths');
    }
};