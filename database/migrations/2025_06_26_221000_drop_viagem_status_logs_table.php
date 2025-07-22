<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::dropIfExists('viagem_status_logs');
    }
    public function down() {
        Schema::create('viagem_status_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('viagem_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status');
            $table->timestamp('created_at')->nullable();
        });
    }
};
