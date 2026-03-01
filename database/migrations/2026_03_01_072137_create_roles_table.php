<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('name', 50)->unique();
        });

        // Seed par défaut
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'employee'],
            ['name' => 'user'],
        ]);

        // ✅ Maintenant que `roles` existe, on peut ajouter la FK sur `users`
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        // Retirer la FK avant de dropper roles
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        Schema::dropIfExists('roles');
    }
};