<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use PDO;

class MakeTenantCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:tenant {name} {subdomain} {database?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //check subdomain is unique
        $subdomain = $this->argument('subdomain');
        if (empty($subdomain)) {
            $this->error('Subdomain is required');
            return;
        }
        $checkSubdomain = Tenant::where('subdomain', $subdomain)->first();
        if ($checkSubdomain) {
            $this->error('Subdomain already exists');
            return;
        }

        //check database is unique
        $database = $this->argument('database');
        if ($database == '') {
            $database = $subdomain;
        }
        $database = str_replace('-', '_', $database);
        $database = config('app.tenant_db_prefix') . $database;
        $checkDatabase = Tenant::where('database', $database)->first();
        if ($checkDatabase) {
            $this->error('Database already exists');
            return;
        }

        //create tenant
        $tenant = new Tenant();
        $tenant->name = $this->argument('name');
        $tenant->subdomain = $subdomain;
        $tenant->database = $database;
        $tenant->save();

        //create database
        $this->createDatabase($database);
        $this->runMigration($database);

        $this->info('Tenant created successfully');

    }

    private function createDatabase($database)
    {
        $connection = config('database.connections.tenant');
        $connection['database'] = null;
        $pdo = new PDO("mysql:host={$connection['host']}", $connection['username'], $connection['password']);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $database");
    }

    private function runMigration($database)
    {
        $connection = config('database.connections.tenant');
        $connection['database'] = $database;
        config(['database.connections.tenant' => $connection]);
        $this->call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
        ]);
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        //name} {subdomain} {database?}
        return [
            'name' => ['Enter the name of the tenant', 'E.g.: Tenant 1'],
            'subdomain' => ['Enter the subdomain of the tenant', 'E.g.: tenant1'],
        ];
    }
}
