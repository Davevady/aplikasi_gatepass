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
        Schema::create('request_drivers', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->unique();
            $table->foreignId('ekspedisi_id')->constrained('ekspedisis')->onDelete('cascade');
            $table->string('nopol_kendaraan');
            $table->string('nama_driver');
            $table->string('no_hp_driver');
            $table->string('nama_kernet')->nullable();
            $table->string('no_hp_kernet')->nullable();
            $table->string('keperluan');
            $table->string('jam_out');
            $table->string('jam_in');
            $table->tinyInteger('acc_admin')->default(1);
            $table->tinyInteger('acc_head_unit')->default(1);
            $table->tinyInteger('acc_security_in')->default(1);
            $table->tinyInteger('acc_security_out')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_drivers');
    }
};
