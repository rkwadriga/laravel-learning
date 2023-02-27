<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\RoleEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table
                ->enum('name', [
                    RoleEnum::GUEST->value,
                    RoleEnum::USER->value,
                    RoleEnum::MANAGER->value,
                    RoleEnum::ADMIN->value,
                ])
                ->default(RoleEnum::USER->value)
            ;
            $table->text('rights')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
