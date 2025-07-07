<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    Protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'contrasena',
        'rol',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    public function cursosComoDocente()
    {
        return $this->hasMany(Curso::class, 'id_docente', 'id_usuario');
    }


    public function cursosComoEstudiante()
    {
        return $this->belongsToMany(Curso::class, 'estudiante_curso', 'id_estudiante', 'id_curso');
    }


    public function asistenciasComoEstudiante()
    {
        return $this->hasMany(Asistencia::class, 'id_estudiante', 'id_usuario');
    }

    public function asistenciasComoDocente()
    {
        return $this->hasMany(Asistencia::class, 'id_docente', 'id_usuario');
    }

    public $timestamps = false;
}