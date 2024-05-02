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
        Schema::create('periode_administrasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('periode', 20);
            $table->year('tahun');
            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->string('nama_fungsi', 10);
            $table->string('slug');
            $table->timestamps();
            $table->float('progres')->nullable();
            $table->integer('amount_file')->nullable();
            $table->integer('complete_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_administrasis');
    }
};
