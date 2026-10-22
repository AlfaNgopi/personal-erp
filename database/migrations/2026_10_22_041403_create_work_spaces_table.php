<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('base_currency'); //e.g USD, EUR, GBP
            $table->string('timezone'); //e.g UTC, GMT, EST
            $table->string('status'); //e.g en_US, fr_FR
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_spaces');
    }
};
