<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeTenantModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:tenant:model {name} {--m|migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant model and optionally a migration in the tenant directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $modelPath = app_path("Models/{$name}.php");

        if (file_exists($modelPath)) {
            $this->error('Model already exists!');
            return;
        }

        // Generate model
        Artisan::call("make:model", ['name' => "{$name}"]);

        if (File::exists($modelPath)) {
            $this->modifyModel($modelPath, $name);
        }

        $this->info("Tenant model {$name} created successfully.");

        // Generate migration in the tenant directory
        if ($this->option('migration')) {
            $tableName = Str::snake($name);
            $tableName = Str::plural($tableName);
            $migrationPath = database_path("migrations/tenant/" . date('Y_m_d_His') . "_create_{$tableName}_table.php");

            Artisan::call("make:migration", [
                'name' => "create_{$tableName}_table",
                '--path' => 'database/migrations/tenant',
            ]);

            $this->info("Migration created: database/migrations/tenant/create_{$tableName}_table.php");
        }

    }

    private function modifyModel($path, $modelName)
    {
        $content = File::get($path);

        // Ensure namespace and use statements
        if (!str_contains($content, "use App\\Trait\\TenantConnection;")) {
            $content = preg_replace('/namespace App\\\Models;/', "namespace App\\Models;\n\nuse App\\Trait\\TenantConnection;", $content);
        }

        // Ensure trait is added correctly inside the class
        if (!str_contains($content, 'use TenantConnection;')) {
            $content = preg_replace('/class ' . $modelName . ' extends Model\s*{/', "class $modelName extends Model\n{\n    use TenantConnection;\n", $content);
        }

        File::put($path, $content);
    }
}
