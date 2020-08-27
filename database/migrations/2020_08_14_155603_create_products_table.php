<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            // $table->unsignedbigInteger('category_id');
            $table->bigInteger('quantity')->unsigned()->default(0);
            $table->decimal('price_current',12,2)->default(0);
            $table->decimal('price_previous',12,2)->default(0);
            $table->integer('discount_percentage')->unsigned()->default(0);
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->text('specs')->nullable();
            $table->text('data_of_interest')->nullable();
            $table->unsignedbigInteger('visits')->default(0);
            $table->unsignedbigInteger('sales')->default(0);
            $table->string('state');
            $table->char('active',2);
            $table->char('slidermain',2);
            $table->timestamps();

            $table->foreignId('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
