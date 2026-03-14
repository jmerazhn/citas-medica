<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SocialCoverageSeeder extends Seeder
{
    public function run(): void
    {
        $coverages = [
            'NINGUNA / PARTICULAR',
            'IHSS (SEGURO SOCIAL)',
            'SEGUROS ATLÁNTIDA',
            'SEGUROS FICOHSA',
            'MAPFRE HONDURAS',
            'BANHCAFE SEGUROS',
            'CREFISA SEGUROS',
        ];

        foreach ($coverages as $name) {
            \App\Models\SocialCoverage::firstOrCreate(['name' => $name]);
        }
    }
}
