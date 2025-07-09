<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('uom', function (Blueprint $table) {
            $table->id();
            $table->integer('qty')->default(1);
            $table->string('satuan', 50);
            $table->timestamps();
            
            // Tambahkan unique constraint untuk kombinasi qty dan satuan
            $table->unique(['qty', 'satuan']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('uom');
    }
};