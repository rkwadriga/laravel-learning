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
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id')->unsigned()->nullable();
                $table->string('name');
                $table->dateTime('created_at')->nullable(false)->useCurrent();
                $table->dateTime('updated_at')->nullable(false)->useCurrent()->useCurrentOnUpdate();
                $table
                    ->foreign('user_id', 'addresses_user_id_fk')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('addresses');
    }
};
