<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidToFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::table('collectible_fields', function (Blueprint $table) use ($driver) {
            // We need a default value when running with sqlite (used in testing)
            if ($driver === 'sqlite') {
                $table->uuid('uuid')->default('')->after('collectible_id');
            } else {
                $table->uuid('uuid')->after('collectible_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collectible_fields', function (Blueprint $table) {
            $table->removeColumn('uuid');
        });
    }
}
