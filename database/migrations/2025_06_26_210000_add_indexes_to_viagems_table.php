<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('viagems', function (Blueprint $table) {
            $table->index('data');
            $table->index('status');
            $table->index('condutor');
            $table->index('placa');
            $table->index('tipo_veiculo');
        });
    }

    public function down()
    {
        Schema::table('viagems', function (Blueprint $table) {
            $table->dropIndex(['data']);
            $table->dropIndex(['status']);
            $table->dropIndex(['condutor']);
            $table->dropIndex(['placa']);
            $table->dropIndex(['tipo_veiculo']);
        });
    }
};
