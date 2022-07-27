# Specified stub migration

The command create migration file with specified stub. Refer from Laravel original "make:migration" command.

## Settings

Add `\MediciVN\SpecifiedStubMigration\MigrateMakeCommand::class` to `app/Console/Kernel.php`

```injectablephp
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \MediciVN\SpecifiedStubMigration\MigrateMakeCommand::class,
    ];

```

Add `MediciVN\SpecifiedStubMigration\MigrationServiceProvider::class` to `config/app.php`

```injectablephp
'providers' => [
    ...
    MediciVN\SpecifiedStubMigration\MigrationServiceProvider::class
],
```

## Example

```injectablephp
php artisan make:specified_stub_migration create_users_table --table=users --stubpath=database/migrations/stubs/create_users_table.stub
```

```injectablephp
Artisan::call("make:specified_stub_migration", [
    "name"          => "create_users_table",
    "--table"       => "users",
    "--stubpath"    => database_path("migrations/stubs/create_users_table.stub"),
]);
```

The content of `database/mirgations/stubs/create_users_table.stub`

```injectablephp
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // The '{{ table }}' is placeholder where the value of `--table` parameter is set
        Schema::create('{{ table }}', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('dob');
            $table->string('whatever_you_want');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{{ table }}');
    }
};
```
