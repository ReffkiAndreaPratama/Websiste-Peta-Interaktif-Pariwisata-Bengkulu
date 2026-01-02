<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinasi_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destinasi_id')
                ->constrained('destinasi')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // rating 1–5
            $table->unsignedTinyInteger('rating');
            // komentar boleh kosong
            $table->text('comment')->nullable();
            // nama guest (kalau mau izinkan non login)
            $table->string('name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinasi_reviews');
    }
};
