<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            $table->string('tipo')->default('sugestao')->after('mensagem');
        });
    }

    public function down(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
