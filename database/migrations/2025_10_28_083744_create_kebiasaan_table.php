<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kebiasaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('murid_id')->constrained('users')->onDelete('cascade');
            $table->time('jam_bangun')->nullable();
            $table->time('jam_tidur')->nullable();
            $table->integer('durasi_belajar')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kebiasaan');
    }
};