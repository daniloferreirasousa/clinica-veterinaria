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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            // Chaves Estrangeiras
            $table->foreignId('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignId('veterinarian_id')->constrained('users');

            // Dados do Agendamento e Atendimento
            $table->dateTime('date_time');
            $table->enum('status', ['agendada', 'em_andamento', 'concluida', 'cancelada'])->default('agendada');
            $table->text('reason');
            $table->text('diagnosis')->nullable();
            $table->text('prescription')->nullable();
            $table->decimal('value', 8,2)->default(0.00);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
