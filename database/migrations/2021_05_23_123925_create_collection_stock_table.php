<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('collection_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedInteger('count');
            $table->string('condition'); // TODO Should this be enabled/disabled at the collectible level?
            $table->string('language'); // TODO Should this be enabled/disabled at the collectible level?
            $table->jsonb('tags'); // TODO Switch to joined table? spatie/laravel-tags?
            $table->timestamps();

            $table->foreign('collection_id')
                  ->references('id')
                  ->on('collections')
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
    public function down(): void
    {
        Schema::dropIfExists('collection_stock');
    }
}
