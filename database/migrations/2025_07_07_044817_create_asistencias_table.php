<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->unsignedBigInteger('id_estudiante');
            $table->unsignedBigInteger('id_curso');
            $table->unsignedBigInteger('id_docente');
            $table->date('fecha');
            $table->enum('estado', ['presente', 'tardanza', 'ausente'])->default('ausente');
            $table->time('hora_registro')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_estudiante')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_curso')->references('id_curso')->on('cursos')->onDelete('cascade');
            $table->foreign('id_docente')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            
            // Índice único para evitar duplicados por estudiante-curso-fecha
            $table->unique(['id_estudiante', 'id_curso', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};