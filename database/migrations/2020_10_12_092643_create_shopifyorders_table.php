<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopifyorders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_id');
            $table->integer('order_number');
            $table->string('email')->nullable();
            $table->string('order_status_url')->nullable();
            $table->string('product_id')->nullable();
            $table->string('title');
            $table->string('quantity')->nullable();
            $table->string('amount')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('vendor')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_business_name')->nullable();
            $table->string('recipient_address1')->nullable();
            $table->string('recipient_city')->nullable();
            $table->string('recipient_zip')->nullable();
             $table->string('recipient_country')->nullable();             


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopifyorders');
    }
}
