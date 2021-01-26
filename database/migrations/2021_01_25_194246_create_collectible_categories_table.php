<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectibleCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('collectible_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('collectible_id');
            $table->string('name');
            $table->jsonb('field_values');
            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('collectible_categories')
                  ->cascadeOnDelete(); // TODO nullOnDelete?

            $table->foreign('collectible_id')
                  ->references('id')
                  ->on('collectibles')
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
        Schema::dropIfExists('collectible_categories');
    }
}
