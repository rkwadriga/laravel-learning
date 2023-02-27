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
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->dateTime('created_at')->nullable(false)->useCurrent();
                $table->dateTime('updated_at')->nullable(false)->useCurrent()->useCurrentOnUpdate();
                $table->unique('name', 'tags_name_uk');
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
        Schema::dropIfExists('tags');
    }
};
