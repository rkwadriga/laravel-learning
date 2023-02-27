<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table
                    ->bigInteger('country_id')
                    ->unsigned()
                    ->nullable()
                ;
                $table
                    ->foreign('country_id', 'users_country_id_fk')
                    ->references('id')
                    ->on('countries')
                    ->nullOnDelete()
                ;
            });
        } catch (QueryException $e) {
            $this->down();
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_country_id_fk');
            $table->dropColumn('country_id');
        });
    }
};
