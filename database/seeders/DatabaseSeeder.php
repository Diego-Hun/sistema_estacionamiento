<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'nombre' => 'Administrador', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Operador', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Supervisor', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('usuarios')->insert([
            [
                'nombre' => 'Admin Principal',
                'correo_electronico' => 'admin@estacionamiento.com',
                'contrasena' => Hash::make('admin123'),
                'id_rol' => 1,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Cajero Turno Día',
                'correo_electronico' => 'operador@estacionamiento.com',
                'contrasena' => Hash::make('operador123'),
                'id_rol' => 2,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Supervisor General',
                'correo_electronico' => 'supervisor@estacionamiento.com',
                'contrasena' => Hash::make('supervisor123'),
                'id_rol' => 3,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('tipos_vehiculo')->insert([
            ['nombre' => 'Auto', 'tarifa_hora' => 10.00, 'tarifa_minuto' => 0.20, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Moto', 'tarifa_hora' => 5.00, 'tarifa_minuto' => 0.10, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Camioneta', 'tarifa_hora' => 15.00, 'tarifa_minuto' => 0.30, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('metodos_pago')->insert([
            ['nombre' => 'Efectivo', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'QR', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        for ($i = 1; $i <= 10; $i++) {
            DB::table('espacios_estacionamiento')->insert([
                'piso' => 'Piso 1',
                'sector' => $i <= 5 ? 'Sector A' : 'Sector B',
                'numero' => 'C-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'esta_ocupado' => 0,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
