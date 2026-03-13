<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SocialCoverageSeeder extends Seeder
{
    public function run(): void
    {
        $coverages = [
            'Ninguna / Particular',
            'IHSS (Seguro Social)',
            'Seguros Atlántida',
            'Seguros FICOHSA',
            'Mapfre Honduras',
            'BANHCAFE Seguros',
            'Crefisa Seguros',
        ];

        foreach ($coverages as $name) {
            \App\Models\SocialCoverage::firstOrCreate(['name' => $name]);
        }
    }
}
