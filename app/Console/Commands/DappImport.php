<?php

namespace App\Console\Commands;

use App\Models\MotivoConsulta;
use App\Models\Patologia;
use App\Models\Patient;
use App\Models\SocialCoverage;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DappImport extends Command
{
    /**
     * Metadatos de configuración por tabla.
     *
     * @var array<string, array{archivo: string, label: string}>
     */
    private const TABLAS = [
        'coberturas' => [
            'archivo' => 'coberturas_sociales.csv',
            'label'   => 'coberturas sociales',
        ],
        'motivos' => [
            'archivo' => 'motivos_consulta.csv',
            'label'   => 'motivos de consulta',
        ],
        'patologias' => [
            'archivo' => 'patologias.csv',
            'label'   => 'patologías',
        ],
        'pacientes' => [
            'archivo' => 'pacientes.csv',
            'label'   => 'pacientes',
        ],
        'madres' => [
            'archivo' => 'madres.csv',
            'label'   => 'madres',
        ],
        'padres' => [
            'archivo' => 'padres.csv',
            'label'   => 'padres',
        ],
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dapp:import
        {tabla : Tabla a importar: coberturas|motivos|patologias|pacientes|madres|padres}
        {--tenant= : Slug/ID del tenant donde importar (sistema multi-tenant)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa datos exportados del sistema DAPP Pediatría (archivos CSV en dapp/output/)';

    // -------------------------------------------------------------------------
    // Entry point
    // -------------------------------------------------------------------------

    public function handle(): int
    {
        $tabla = strtolower($this->argument('tabla'));

        if (! array_key_exists($tabla, self::TABLAS)) {
            $this->error("Tabla desconocida: \"{$tabla}\"");
            $this->line('Valores válidos: ' . implode(', ', array_keys(self::TABLAS)));

            return self::FAILURE;
        }

        // Las tablas de padres/madres requieren vinculación manual a pacientes
        if ($tabla === 'madres' || $tabla === 'padres') {
            return $this->handleParents($tabla);
        }

        // Inicializar tenancy si se proporcionó --tenant
        if ($tenantSlug = $this->option('tenant')) {
            if (! $this->initializeTenant($tenantSlug)) {
                return self::FAILURE;
            }
        }

        $meta    = self::TABLAS[$tabla];
        $csvPath = base_path("dapp/output/{$meta['archivo']}");

        if (! file_exists($csvPath)) {
            $this->error("Archivo CSV no encontrado: {$csvPath}");
            $this->line('Ejecute primero: python dapp/extract_all.py');

            return self::FAILURE;
        }

        $rows = $this->readCsv($csvPath);

        if (empty($rows)) {
            $this->warn("El archivo CSV está vacío o no tiene filas válidas: {$csvPath}");

            return self::SUCCESS;
        }

        $total = count($rows);
        $this->info(sprintf(
            'Importando %s desde %s (%s filas)...',
            $meta['label'],
            $meta['archivo'],
            number_format($total)
        ));

        $imported = 0;
        $skipped  = 0;

        $this->withProgressBar($rows, function (array $row) use ($tabla, &$imported, &$skipped) {
            $created = $this->importRow($tabla, $row);
            $created ? $imported++ : $skipped++;
        });

        $this->newLine(2);
        $this->info(sprintf(
            'Completado: %s importados, %s omitidos (ya existían).',
            number_format($imported),
            number_format($skipped)
        ));

        return self::SUCCESS;
    }

    // -------------------------------------------------------------------------
    // Importación por tabla
    // -------------------------------------------------------------------------

    /**
     * Despacha la importación de una fila al método correspondiente.
     * Devuelve true si se creó un registro nuevo, false si ya existía.
     */
    private function importRow(string $tabla, array $row): bool
    {
        return match ($tabla) {
            'coberturas' => $this->importCobertura($row),
            'motivos'    => $this->importMotivo($row),
            'patologias' => $this->importPatologia($row),
            'pacientes'  => $this->importPaciente($row),
            default      => false,
        };
    }

    private function importCobertura(array $row): bool
    {
        $nombre = $this->sanitize($row['nombre'] ?? '');
        if ($nombre === '') {
            return false;
        }

        $model = SocialCoverage::firstOrCreate(['name' => mb_strtoupper($nombre)]);

        return $model->wasRecentlyCreated;
    }

    private function importMotivo(array $row): bool
    {
        $nombre = $this->sanitize($row['nombre'] ?? '');
        if ($nombre === '') {
            return false;
        }

        $model = MotivoConsulta::firstOrCreate(['nombre' => mb_strtoupper($nombre)]);

        return $model->wasRecentlyCreated;
    }

    private function importPatologia(array $row): bool
    {
        $nombre = $this->sanitize($row['nombre'] ?? '');
        if ($nombre === '') {
            return false;
        }

        $model = Patologia::firstOrCreate(['nombre' => mb_strtoupper($nombre)]);

        return $model->wasRecentlyCreated;
    }

    private function importPaciente(array $row): bool
    {
        $nombres   = $this->sanitize($row['nombres'] ?? '');
        $apellidos = $this->sanitize($row['apellidos'] ?? '');

        // Si faltan los campos separados, intentar derivarlos del nombre completo
        // (formato exportado: "APELLIDO, NOMBRE")
        if ($nombres === '' && $apellidos === '') {
            $completo = $this->sanitize($row['nombre_completo'] ?? '');
            if ($completo === '') {
                return false;
            }
            $parts     = explode(',', $completo, 2);
            $apellidos = trim($parts[0]);
            $nombres   = isset($parts[1]) ? trim($parts[1]) : '';
        }

        if ($apellidos === '') {
            return false;
        }

        // Parsear fecha de nacimiento (formato YYYY-MM-DD del CSV)
        $fechaNacimiento = null;
        $rawFecha        = trim($row['fecha_nacimiento'] ?? '');
        if ($rawFecha !== '') {
            try {
                $fechaNacimiento = Carbon::parse($rawFecha)->toDateString();
            } catch (\Throwable) {
                $fechaNacimiento = null;
            }
        }

        $model = Patient::firstOrCreate(
            [
                'apellidos' => mb_strtoupper($apellidos),
                'nombres'   => mb_strtoupper($nombres),
            ],
            [
                'fecha_nacimiento' => $fechaNacimiento,
                'domicilio'        => $this->sanitize($row['direccion'] ?? ''),
                'ciudad'           => $this->sanitize($row['ciudad'] ?? ''),
                'telefono'         => $this->sanitize($row['telefono'] ?? ''),
            ]
        );

        return $model->wasRecentlyCreated;
    }

    /**
     * Las tablas madres/padres no se importan automáticamente porque la vinculación
     * a registros de pacientes (campo madre/padre) requiere revisión manual para
     * evitar asociaciones incorrectas por coincidencia de apellidos.
     *
     * Los archivos CSV quedan disponibles en dapp/output/ para consulta.
     * Para vincular manualmente: Patient::find($id)->update(['madre' => $nombreCompleto])
     */
    private function handleParents(string $tabla): int
    {
        $meta    = self::TABLAS[$tabla];
        $csvPath = base_path("dapp/output/{$meta['archivo']}");

        $this->warn("Importación de {$meta['label']} no implementada de forma automática.");
        $this->newLine();
        $this->line('  La vinculación de madres/padres a pacientes requiere revisión');
        $this->line('  manual para evitar asociaciones incorrectas por similitud de');
        $this->line('  apellidos.');
        $this->newLine();
        $this->line("  CSV disponible para consulta: {$csvPath}");
        $this->newLine();
        $campo = $tabla === 'madres' ? 'madre' : 'padre';
        $this->line('  Para vincular un registro manualmente (Tinker o migración):');
        $this->line("    Patient::find(\$id)->update(['{$campo}' => \$nombreCompleto])");

        return self::SUCCESS;
    }

    // -------------------------------------------------------------------------
    // Tenancy
    // -------------------------------------------------------------------------

    /**
     * Inicializa el contexto de tenant por slug/ID.
     * Devuelve false si el tenant no existe.
     */
    private function initializeTenant(string $slug): bool
    {
        $tenant = Tenant::find($slug);

        if (! $tenant) {
            $this->error("Tenant no encontrado: \"{$slug}\"");
            $this->line('Slugs disponibles: ' . Tenant::pluck('id')->implode(', '));

            return false;
        }

        tenancy()->initialize($tenant);
        $this->info("Tenant inicializado: {$tenant->nombre} ({$slug})");

        return true;
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Lee un archivo CSV y devuelve un array de arrays asociativos.
     * Maneja archivos con o sin BOM (UTF-8-sig).
     */
    private function readCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->error("No se pudo abrir el archivo: {$path}");

            return [];
        }

        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);

            return [];
        }

        // Eliminar BOM del primer campo si está presente
        $header[0] = ltrim($header[0], "\xEF\xBB\xBF");
        $header    = array_map('trim', $header);

        $rows = [];
        while (($line = fgetcsv($handle)) !== false) {
            // Rellenar columnas faltantes con string vacío
            $line   = array_pad($line, count($header), '');
            $rows[] = array_combine($header, array_slice($line, 0, count($header)));
        }

        fclose($handle);

        return $rows;
    }

    /**
     * Limpia un string eliminando espacios sobrantes y caracteres de control.
     */
    private function sanitize(string $value): string
    {
        return trim(preg_replace('/[\x00-\x1F\x7F]/', '', $value));
    }
}
