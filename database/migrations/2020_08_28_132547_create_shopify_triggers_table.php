<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_triggers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('trigger_name');
            $table->string('trigger_card')->nullable();
            $table->text('trigger_message')->nullable();
            $table->text('trigger_signoff')->nullable();
            $table->string('trigger_handwriting_style')->nullable();
            $table->string('trigger_insert')->nullable();
            $table->string('trigger_gift_card')->nullable();
            $table->string('trigger_status')->default();
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
        Schema::dropIfExists('shopify_triggers');
    }
}
