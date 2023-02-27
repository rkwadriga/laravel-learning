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
            Schema::table('role_user', function (Blueprint $table) {
                $table
                    ->foreign('user_id', 'role_user_user_id_fk')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete()
                ;
                $table
                    ->foreign('role_id', 'role_user_role_id_fk')
                    ->references('id')
                    ->on('roles')
                    ->cascadeOnDelete()
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
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign('role_user_user_id_fk');
            $table->dropForeign('role_user_role_id_fk');
        });
    }
};
