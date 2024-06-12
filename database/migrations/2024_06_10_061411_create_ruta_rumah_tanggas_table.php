<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ruta_rumah_tanggas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date("tanggal");
            $table->string("ruta");
            $table->integer("progres")->nullable();
            $table->foreignId('pemutakhiran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_rumah_tanggas');
    }
};
