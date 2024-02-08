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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->integer('user_id'); //(внешний ключ, связь с пользователем)
            $table->double('amount');//(сумма транзакции)
            $table->integer('ttype'); //((тип транзакции,  0 - "приход" , 1 - "расход"))
            $table->string('description')->nullable();//(описание транзакции)
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
