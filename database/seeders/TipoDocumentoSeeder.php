<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { {
            $documentos = [
                ['codigo' => 'DNI', "descripcion" => 'Documento Nacional de Identidad'],
                ['codigo' => 'CUIL', "descripcion" => 'Código Único de Identificación Laboral'],
                ['codigo' => 'CUIT', "descripcion" => 'Código Único de Identificación Tributaria'],
                ['codigo' => 'LE', "descripcion" => 'Libreta de Enrolamiento'],
                ['codigo' => 'LC', "descripcion" => 'Libreta Cívica'],
                ['codigo' => 'PAS', "descripcion" => 'Pasaporte'],
            ];

            foreach ($documentos as $doc) {
                TipoDocumento::firstOrCreate($doc);
            }
        }
    }
}
