<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefone')->nullable();
            $table->string('cargo')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('foto_perfil')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telefone', 'cargo', 'data_nascimento', 'foto_perfil']);
        });
    }
};
