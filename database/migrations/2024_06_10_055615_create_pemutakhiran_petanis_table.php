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
        Schema::create('pemutakhiran_petanis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float("penyelesaian_ruta", 50)->nullable()->default(0);
            $table->foreignId("kegiatan_id");
            $table->string("id_pml", 50);
            $table->string("pml", 100);
            $table->string("id_ppl", 50);
            $table->string("ppl", 100);
            $table->string("kode_kec", 4);
            $table->string("kecamatan", 20);
            $table->string("kode_desa", 4);
            $table->string("desa", 20);
            $table->string("nbs", 100);
            $table->string("nks", 100);
            $table->string("nama_sls", 100);
            $table->integer("beban_kerja");
            $table->integer("keluarga_awal");
            $table->integer("keluarga_akhir");
            $table->text("note")->nullable();
            $table->boolean("status")->default(false);
            $table->date('tgl_awal');
            $table->date('tgl_akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemutakhiran_petanis');
    }
};
