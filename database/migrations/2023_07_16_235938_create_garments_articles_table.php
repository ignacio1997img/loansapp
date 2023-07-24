<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarmentsArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garments_articles', function (Blueprint $table) {               
            $table->id();
            $table->foreignId('garment_id')->nullable()->constrained('garments');

            $table->foreignId('category_id')->nullable()->constrained('category_garments');
            $table->string('category')->nullable();
            

            $table->decimal('amountSubTotal',11,2)->nullable();

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
        Schema::dropIfExists('garments_articles');
    }
}
