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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string("judul", 550);
            $table->string("namaFile")->nullable();
            $table->string("file")->nullable();
            $table->float('ukuran_file')->default(0); // Kolom untuk menyimpan ukuran file dalam byte
            $table->foreignId("transaksi_id");
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
