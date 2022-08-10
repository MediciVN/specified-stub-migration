<?php

namespace MediciVN\SpecifiedStubMigration;

use Exception;
use Illuminate\Database\Migrations\MigrationCreator as BaseMigrationCreator;

class MigrationCreator extends BaseMigrationCreator
{
    /**
     * Create a new migration at the given path.
     *
     * @param string $name
     * @param string $path
     * @param string $table
     * @param string $stubPath
     * @param bool   $noDatePrefix
     * @return string
     * @throws Exception
     */
    public function createByStub(string $name, string $path, string $table, string $stubPath, $noDatePrefix = false): string
    {
        $this->ensureMigrationDoesntAlreadyExist($name, $path);

        $path = $this->getPath($name, $path, $noDatePrefix);

        // get stub file
        $stub = $this->files->get($stubPath);

        // make directory if not exists
        $this->files->ensureDirectoryExists(dirname($path));

        // create migration file from stub file
        $this->files->put($path, $this->populateStub($stub, $table));

        // Next, we will fire any hooks that are supposed to fire after a migration is
        // created. Once that is done we'll be ready to return the full path to the
        // migration file so it can be used however it's needed by the developer.
        $this->firePostCreateHooks($table, $path);

        return $path;
    }

    /**
     * Get the full path to the migration.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     */
    protected function getPath($name, $path, $noDatePrefix = true)
    {
        if ($noDatePrefix) {
            return $path . '/' . $name . '.php';
        }

        return $path . '/' . $this->getDatePrefix() . '_' . $name . '.php';
    }
}
