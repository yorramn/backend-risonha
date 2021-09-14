<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocaos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("codigo");
            $table->string("descricao");
            $table->string("onde_aplicar");
            $table->string("como_aplicar");
            $table->double("valor");
            $table->date("data_de_validade");
            $table->foreignId("user_id")->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocaos');
    }
}
