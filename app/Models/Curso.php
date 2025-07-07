<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';
    protected $primaryKey = 'id_curso';
    protected $fillable = ['codigo_curso', 'nombre_curso', 'creditos', 'descripcion', 'id_docente'];

    // Relación con el docente (1 a muchos)
    public function docente()
    {
        return $this->belongsTo(Usuario::class, 'id_docente', 'id_usuario');
    }

    // Relación con los estudiantes (muchos a muchos)
    public function estudiantes()
    {
        return $this->belongsToMany(Usuario::class, 'estudiante_curso', 'id_curso', 'id_estudiante');
    }

    // Relación con los horarios (1 a muchos)
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'id_curso');
    }

    // Relación con los docentes a través de la tabla pivote (muchos a muchos)
    public function docentes()
    {
        return $this->belongsToMany(Usuario::class, 'docente_curso', 'id_curso', 'id_docente');
    }

    // Relación con los estudiantes a través de la tabla pivote (muchos a muchos)
    public function estudiantesCurso()
    {
        return $this->belongsToMany(Usuario::class, 'estudiante_curso', 'id_curso', 'id_estudiante');
    }

    // Relación con horarios
    public function horariosCurso()
    {
        return $this->hasMany(Horario::class, 'id_curso');
    }

    // Opcional: para obtener todos los cursos y sus docentes/estudiantes de una sola vez
    public static function getCursosConDetalles()
    {
        return self::with(['docentes', 'estudiantesCurso', 'horariosCurso'])->get();
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_curso', 'id_curso');
    }
}

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';
    protected $primaryKey = 'id_horario';
    protected $fillable = ['id_curso', 'dia_semana', 'hora_inicio', 'hora_fin'];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }
}
