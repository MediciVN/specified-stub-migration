<?php

namespace MediciVN\SpecifiedStubMigration;

use Exception;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

/**
 * refer: vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateMakeCommand.php
 */
class MigrateMakeCommand extends BaseCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:specified_stub_migration
        {name : The name of the migration}
        {--table= : The table to migrate}
        {--stubpath= : The location  of the stub file to create migration files}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}
        {--no-date-prefix : date prefix in filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file';

    /**
     * The migration creator instance.
     *
     * @var \MediciVN\SpecifiedStubMigration\MigrationCreator
     */
    protected MigrationCreator $creator;

    /**
     * The Composer instance.
     *
     * @var Composer
     */
    protected Composer $composer;

    /**
     * Create a new migration install command instance.
     *
     * @param \MediciVN\SpecifiedStubMigration\MigrationCreator $creator
     * @param Composer $composer
     * @return void
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct();

        $this->creator = $creator;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->writeMigration();

        $this->composer->dumpAutoloads();
    }

    /**
     * Write the migration file to disk.
     *
     * @param string $name
     * @param string $table
     * @param string $stubpath
     * @return void
     * @throws Exception
     */
    protected function writeMigration(): void
    {
        $name           = Str::snake(trim($this->input->getArgument('name')));
        $table          = $this->input->getOption('table');
        $stubpath       = $this->input->getOption('stubpath');
        $noDatePrefix  = $this->input->getOption('no-date-prefix');

        $file = $this->creator->createByStub(
            $name,
            $this->getMigrationPath(),
            $table,
            $stubpath,
            $noDatePrefix,
        );

        if (!$this->option('fullpath')) {
            $file = pathinfo($file, PATHINFO_FILENAME);
        }

        $this->components->info(sprintf('Created migration [%s].', $file));
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath(): string
    {
        if (!is_null($targetPath = $this->input->getOption('path'))) {
            return !$this->usingRealPath()
                ? $this->laravel->basePath() . '/' . $targetPath
                : $targetPath;
        }

        return parent::getMigrationPath();
    }
}
