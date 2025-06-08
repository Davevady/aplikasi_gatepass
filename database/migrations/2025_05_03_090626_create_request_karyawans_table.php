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
        Schema::create('request_karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->unique();
            $table->string('nama');
            $table->string('no_telp');
            $table->foreignId('departemen_id')->constrained('departemens')->onDelete('cascade');
            $table->string('keperluan');
            $table->string('jam_out');
            $table->string('jam_in');
            $table->tinyInteger('acc_lead')->default(1);
            $table->tinyInteger('acc_hr_ga')->default(1);
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
        Schema::dropIfExists('request_karyawans');
    }
};
