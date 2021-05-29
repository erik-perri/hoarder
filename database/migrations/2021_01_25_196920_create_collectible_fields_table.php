<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectibleFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::create('collectible_fields', function (Blueprint $table) use ($driver) {
            $table->id();
            $table->unsignedBigInteger('collectible_id');

            // We need a default value when running with sqlite (used in testing)
            if ($driver === 'sqlite') {
                $table->uuid('uuid')->default('');
            } else {
                $table->uuid('uuid');
            }

            $table->string('entity_type', 8);
            $table->string('code', 32);
            $table->string('name', 32);
            $table->string('input_type', 16);
            $table->jsonb('input_options');
            $table->boolean('is_required');
            $table->timestamps();

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
        Schema::dropIfExists('collectible_fields');
    }
}
