<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('viagems', function (Blueprint $table) {
            $table->time('hora_chegada')->nullable()->change();
            $table->integer('km_chegada')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('viagems', function (Blueprint $table) {
            $table->time('hora_chegada')->nullable(false)->change();
            $table->integer('km_chegada')->nullable(false)->change();
        });
    }
};
