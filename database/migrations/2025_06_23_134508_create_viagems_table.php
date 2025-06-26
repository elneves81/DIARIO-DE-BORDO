<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('viagems', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->time('hora_saida');
            $table->integer('km_saida');
            $table->string('destino');
            $table->time('hora_chegada');
            $table->integer('km_chegada');
            $table->string('condutor');
            $table->string('num_registro_abastecimento')->nullable();
            $table->decimal('quantidade_abastecida', 8, 2)->nullable();
            $table->string('tipo_veiculo')->nullable();
            $table->string('placa')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('viagems');
    }
};