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
        Schema::create('ekspedisis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ekspedisi');
            $table->string('alamat');
            $table->string('no_telp');
            $table->string('email')->nullable();
            $table->string('pic');
            $table->string('no_hp_pic');
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekspedisis');
    }
};
