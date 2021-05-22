<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectibleStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectible_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedInteger('count');
            $table->string('condition');
            $table->string('language');
            $table->jsonb('tags'); // TODO Switch to joined table? spatie/laravel-tags?
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();

            // TODO We should probably not delete the stock record if an item is deleted. One potential option is
            //      nullOnDelete while storing a copy of the item name to list as an unattached stock entry (though
            //      keeping the item name up to date could be a pain)
            $table->foreign('item_id')
                  ->references('id')
                  ->on('collectible_items')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collectible_stock');
    }
}
