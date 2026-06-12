<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('encryption_key_jwk')->nullable()->after('password');
            $table->string('encryption_key_salt')->nullable()->after('encryption_key_jwk');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['encryption_key_jwk', 'encryption_key_salt']);
        });
    }
};
