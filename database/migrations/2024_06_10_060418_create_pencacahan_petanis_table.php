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
        Schema::create('pencacahan_petanis', function (Blueprint $table) {
            $table->id();
            $table->foreignId("kegiatan_id");
            $table->string("kode_kec", 4);
            $table->string("kecamatan", 20);
            $table->string("kode_desa", 4);
            $table->string("desa", 20);
            $table->string("id_pml", 50);
            $table->string("pml", 100);
            $table->string("id_ppl", 50);
            $table->string("ppl", 100);
            $table->string("nks", 100);
            $table->string("jenis_komoditas", 50)->nullable();
            $table->string("nama_krt", 100)->nullable();
            $table->boolean("status")->default(false);
            $table->date('tgl_awal');
            $table->date('tgl_akhir');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencacahan_petanis');
    }
};
