<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');             // Nombre de la carta
            $table->string('url_imagen')->nullable(); // URL de la imagen (opcional)
            // No ponemos category_id aquí porque lo añadiremos en migración aparte
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
