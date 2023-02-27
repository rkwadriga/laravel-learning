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
            Schema::create('photos', function (Blueprint $table) {
                $table->id();
                $table->string('path');
                $table->bigInteger('image_able_id')->unsigned();
                $table->string('image_able_type', 64);
                $table->dateTime('created_at')->nullable(false)->useCurrent();
                $table->dateTime('updated_at')->nullable(false)->useCurrent()->useCurrentOnUpdate();
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
        Schema::dropIfExists('photos');
    }
};
