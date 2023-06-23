<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarmentsDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garments_docs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garment_id')->nullable()->constrained('garments');
            $table->text('image')->nullable();
            
            $table->foreignId('register_userId')->nullable()->constrained('users');
            $table->string('register_agentType')->nullable();
            $table->timestamps();
            $table->foreignId('deleted_userId')->nullable()->constrained('users');
            $table->string('deleted_agentType')->nullable();
            $table->text('deleteObservation')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('garments_docs');
    }
}
