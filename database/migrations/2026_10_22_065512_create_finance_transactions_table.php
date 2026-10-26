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
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_space_id')->constrained('work_spaces')->onDelete('cascade');
            $table->foreignId('finance_merchant_id')->constrained('finance_merchants');
            $table->foreignId('finance_category_id')->nullable()->constrained('finance_categories');
            $table->integer('amount');
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
