<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarmentsMonthAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garments_month_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garmentMonth_id')->nullable()->constrained('garments_months');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions');
            $table->foreignId('cashier_id')->nullable()->constrained('cashiers');

            
            $table->decimal('amount',11, 2)->nullable();
            $table->foreignId('agent_id')->nullable()->constrained('users');
            $table->string('agentType')->nullable();

            $table->smallInteger('status')->default(1);

            $table->timestamps();            

            $table->softDeletes();            
            $table->foreignId('deleted_userId')->nullable()->constrained('users');
            $table->string('deleted_agentType')->nullable();
            $table->text('deleteObservation')->nullable();
            $table->string('deletedKey')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('garments_month_agents');
    }
}
