<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("codigo")->unique();
            $table->string("nome");
            $table->date("data_de_validade");
            $table->integer("quantidade");
            $table->string("tipo_de_quantidade");
            $table->double("peso");
            $table->string("tipo_de_peso");
            $table->string("fabricante");
            $table->float("preco");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
