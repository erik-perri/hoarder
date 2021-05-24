<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionGoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('collection_goal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->string('name');

            // TODO Consider merging criteria and having a single builder
            $table->text('category_criteria');
            $table->text('item_criteria');
            $table->text('stock_criteria');

            $table->timestamps();

            $table->foreign('collection_id')
                  ->references('id')
                  ->on('collections')
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
        Schema::dropIfExists('collection_goal');
    }
}
