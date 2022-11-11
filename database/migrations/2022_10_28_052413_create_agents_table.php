<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('people_id')->nullable()->constrained('people');
            $table->foreignId('agentType_id')->nullable()->constrained('agent_types');
            $table->text('observation')->nullable();


            $table->smallInteger('status')->default(1);
            $table->timestamps();
            $table->foreignId('register_userId')->nullable()->constrained('users');
            $table->softDeletes();
            $table->foreignId('deleted_userId')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
