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
        Schema::create('pencacahan_rumah_tanggas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("kegiatan_id");
            $table->string("id_pml", 50);
            $table->string("pml", 100);
            $table->string("id_ppl", 50);
            $table->string("ppl", 100);
            $table->string("kode_kec", 4);
            $table->string("kecamatan", 20);
            $table->string("kode_desa", 4);
            $table->string("desa", 20);
            $table->string("nks", 50);
            $table->string("sampel_1")->nullable();
            $table->string("sampel_2")->nullable();
            $table->string("sampel_3")->nullable();
            $table->string("sampel_4")->nullable();
            $table->string("sampel_5")->nullable();
            $table->string("sampel_6")->nullable();
            $table->string("sampel_7")->nullable();
            $table->string("sampel_8")->nullable();
            $table->string("sampel_9")->nullable();
            $table->string("sampel_10")->nullable();
            $table->boolean("status")->default(false);
            $table->integer("progres")->default(0);
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
        Schema::dropIfExists('pencacahan_rumah_tanggas');
    }
};
