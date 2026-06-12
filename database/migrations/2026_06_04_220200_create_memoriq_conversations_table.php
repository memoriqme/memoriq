<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memoriq_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('payload_version')->default(2);
            $table->longText('encrypted_header');
            $table->longText('encrypted_body')->nullable();
            $table->string('body_storage_disk')->nullable();
            $table->string('body_storage_path')->nullable();
            $table->unsignedBigInteger('body_bytes')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memoriq_conversations');
    }
};
