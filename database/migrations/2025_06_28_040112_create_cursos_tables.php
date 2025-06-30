<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id('id_curso');
            $table->string('codigo_curso')->unique();
            $table->string('nombre_curso');
            $table->integer('creditos')->default(3);
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_docente');
            $table->foreign('id_docente')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
        
        Schema::create('docente_curso', function (Blueprint $table) {
            $table->unsignedBigInteger('id_docente');
            $table->unsignedBigInteger('id_curso');
            $table->foreign('id_docente')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_curso')->references('id_curso')->on('cursos')->onDelete('cascade');
            $table->primary(['id_docente', 'id_curso']);
        });

        Schema::create('estudiante_curso', function (Blueprint $table) {
            $table->unsignedBigInteger('id_estudiante');
            $table->unsignedBigInteger('id_curso');
            $table->foreign('id_estudiante')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_curso')->references('id_curso')->on('cursos')->onDelete('cascade');
            $table->primary(['id_estudiante', 'id_curso']);
        });

        Schema::create('horarios', function (Blueprint $table) {
            $table->id('id_horario');
            $table->unsignedBigInteger('id_curso');
            $table->foreign('id_curso')->references('id_curso')->on('cursos')->onDelete('cascade');
            $table->string('dia_semana');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('docente_curso');
        Schema::dropIfExists('estudiante_curso');
        Schema::dropIfExists('horarios');
    }
};
