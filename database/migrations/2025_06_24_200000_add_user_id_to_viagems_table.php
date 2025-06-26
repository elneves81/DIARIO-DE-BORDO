<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Viagem;

return new class extends Migration
{
    public function up()
    {
        Schema::table('viagems', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Migrar dados existentes: associar user_id pelo nome do condutor
        $viagens = Viagem::all();
        foreach ($viagens as $viagem) {
            $user = User::where('name', $viagem->condutor)->first();
            if ($user) {
                $viagem->user_id = $user->id;
                $viagem->save();
            }
        }
    }

    public function down()
    {
        Schema::table('viagems', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
