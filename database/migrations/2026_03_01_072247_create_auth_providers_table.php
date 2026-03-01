<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_providers', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('name', 50)->unique(); // google, facebook, etc.
        });

        DB::table('auth_providers')->insert([
            ['name' => 'local'],
            ['name' => 'google'],
            ['name' => 'facebook'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_providers');
    }
};