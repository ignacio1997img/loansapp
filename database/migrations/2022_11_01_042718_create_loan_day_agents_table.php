<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanDayAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_day_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loanDay_id')->nullable()->constrained('loan_days');
            
            $table->decimal('amount',11, 2)->nullable();
            $table->foreignId('agent_id')->nullable()->constrained('users');
            $table->string('agentType')->nullable();

            $table->smallInteger('status')->default(1);

            $table->timestamps();            

            $table->softDeletes();            
            $table->foreignId('deleted_userId')->nullable()->constrained('users');
            $table->string('deleted_agentType')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_day_agents');
    }
}
