<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("title");
            $table->string("description")->nullable();
            $table->enum('type', ['Planung', 'Neues Feature', 'Bug', 'Datenmodell'])->nullable();
            $table->unsignedInteger("status")->default(0);
            $table->date("due_date")->nullable();
            $table->unsignedInteger("priority")->default(1);
            $table->string('assignee')->nullable();
            $table->foreignId("board_id")->constrained("boards");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
