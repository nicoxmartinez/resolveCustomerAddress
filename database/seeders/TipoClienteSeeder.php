<?php

namespace Database\Seeders;

use App\Models\TipoCliente;
use Illuminate\Database\Seeder;

class TipoClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['descripcion' => 'Persona Física'],
            ['descripcion' => 'Persona Jurídica'],
        ];

        foreach ($tipos as $tipo) {
            TipoCliente::firstOrCreate($tipo);
        }
    }
}
