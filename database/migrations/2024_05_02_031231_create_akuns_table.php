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
        Schema::create('akuns', function (Blueprint $table) {
            $table->id();
            $table->string("nama", 550);
            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->float('progres')->nullable()->default(0);
            $table->integer('amount_file')->nullable()->default(0);
            $table->integer('complete_file')->nullable()->default(0);
            $table->foreignId("kegiatan_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuns');
    }
};
