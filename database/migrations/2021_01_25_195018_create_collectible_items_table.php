<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectibleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('collectible_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collectible_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->jsonb('field_values');
            $table->timestamps();

            $table->foreign('collectible_id')
                  ->references('id')
                  ->on('collectibles')
                  ->cascadeOnDelete();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('collectible_categories')
                  ->cascadeOnDelete();

            $table->foreign('parent_id')
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
        Schema::dropIfExists('collectible_items');
    }
}
