<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarmentsArticlesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garments_articles_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garmentArticle_id')->nullable()->constrained('garments_articles');

            $table->string('foreign_id')->nullable();
            $table->string('typeForeign')->nullable();           


            $table->string('title')->nullable();
            $table->string('value')->nullable();

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
        Schema::dropIfExists('garments_articles_details');
    }
}
