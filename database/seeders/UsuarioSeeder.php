<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Jose Carlos',
            'apellido' => 'Iquise Pari',
            'correo' => 'admin@example.com',
            'contrasena' => Hash::make('admin123'),
            'rol' => 'administrador',
        ]);

        Usuario::create([
            'nombre' => 'Ana Rebeca',
            'apellido' => 'Ccopa Mamani',
            'correo' => 'docente@example.com',
            'contrasena' => Hash::make('docente123'),
            'rol' => 'docente',
        ]);

        Usuario::create([
            'nombre' => 'Ronald Alex',
            'apellido' => 'Diaz Pari',
            'correo' => 'estudiante@example.com',
            'contrasena' => Hash::make('estudiante123'),
            'rol' => 'estudiante',
        ]);
    }
}
