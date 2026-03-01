<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ce fichier s'exécute EN PREMIER (timestamp 0001).
     *
     * ⚠️  On crée `users` ICI (sans FK vers `roles`) car
     *     `user_auths` en a besoin dès la migration suivante.
     *
     * La FK users.role_id → roles.id est ajoutée DANS la
     * migration create_roles_table, une fois `roles` créée.
     */
    public function up(): void
    {
        // ─── users (sans contrainte FK vers roles) ──────────────────────────
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('password_hash')->nullable();
            $table->date('birth_date')->nullable();
            $table->smallInteger('role_id')->default(3);
            $table->timestamps();
            // ⚠️  Pas de FK ici — roles n'existe pas encore à ce stade
        });

        // ─── Tables système Laravel ─────────────────────────────────────────
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};