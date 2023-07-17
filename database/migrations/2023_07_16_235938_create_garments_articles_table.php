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
            $table->foreignId('article_id')->nullable()->constrained('article');

            $table->string('article')->nullable();

            $table->decimal('amountLoan',11,2)->nullable();

            $table->decimal('amountCant',11,2)->nullable();

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
