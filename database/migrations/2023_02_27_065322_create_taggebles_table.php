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
            Schema::create('taggables', function (Blueprint $table) {
                $table->bigInteger('tag_id')->unsigned();
                $table->bigInteger('taggable_id')->unsigned();
                $table->string('taggable_type', 64);
                $table->primary(['tag_id', 'taggable_id', 'taggable_type']);
                $table->index('tag_id', 'taggables_tag_id_i');
                $table->index('taggable_id', 'taggables_taggable_id_i');
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
        Schema::dropIfExists('taggables');
    }
};
