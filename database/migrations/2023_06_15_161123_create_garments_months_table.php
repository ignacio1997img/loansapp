<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarmentsMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garments_months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garment_id')->nullable()->constrained('garments');
            $table->date('start')->nullable();
            $table->date('finish')->nullable();

            $table->decimal('amount',11,2)->nullable();


            $table->timestamps();
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
        Schema::dropIfExists('garments_months');
    }
}
