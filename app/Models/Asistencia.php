<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';
    
    protected $fillable = [
        'id_estudiante',
        'id_curso',
        'id_docente',
        'fecha',
        'estado',
        'hora_registro',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_registro' => 'datetime'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Usuario::class, 'id_estudiante', 'id_usuario');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function docente()
    {
        return $this->belongsTo(Usuario::class, 'id_docente', 'id_usuario');
    }
}