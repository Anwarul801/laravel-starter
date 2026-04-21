<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeModule extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create Model, Migration, Controller, Service, Repository';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        // 1. Model + Migration + Controller
        $this->call('make:model', [
            'name' => $name,
            '-m' => true,
            '-c' => true,
            '-r' => true,
        ]);

        // 2. Service
        $this->createService($name);

        // 3. Repository
        $this->createRepository($name);

        // 4. Inject Service into Controller
        $this->updateController($name);

        $this->info('✅ Module created successfully!');
    }

    protected function createService($name)
    {
        $path = app_path("Services/{$name}Service.php");
        File::ensureDirectoryExists(app_path('Services'));

        $content = <<<PHP
<?php

namespace App\Services;

use App\Repositories\\{$name}Repository;

class {$name}Service
{
    protected \$repo;

    public function __construct({$name}Repository \$repo)
    {
        \$this->repo = \$repo;
    }

    public function list()
    {
        return \$this->repo->all();
    }

    public function create(array \$data)
    {
        return \$this->repo->create(\$data);
    }
}
PHP;

        File::put($path, $content);
    }

    protected function createRepository($name)
    {
        $path = app_path("Repositories/{$name}Repository.php");
        File::ensureDirectoryExists(app_path('Repositories'));

        $content = <<<PHP
<?php

namespace App\Repositories;

use App\Models\\{$name};

class {$name}Repository
{
    public function all()
    {
        return {$name}::latest()->get();
    }

    public function create(array \$data)
    {
        return {$name}::create(\$data);
    }
}
PHP;

        File::put($path, $content);
    }

    protected function updateController($name)
    {
        $path = app_path("Http/Controllers/{$name}Controller.php");

        if (!File::exists($path)) {
            return;
        }

        $content = File::get($path);

        // Add Service use statement
        if (!str_contains($content, "use App\Services\\{$name}Service;")) {
            $content = str_replace(
                "use Illuminate\Http\Request;\n",
                "use Illuminate\Http\Request;\nuse App\Services\\{$name}Service;\n",
                $content
            );
        }

        // Inject constructor only once (after class declaration)
        $pattern = "/class {$name}Controller extends Controller\s*\{/";

        $replacement = "class {$name}Controller extends Controller\n{\n    protected \${$name}Service;\n\n    public function __construct({$name}Service \${$name}Service)\n    {\n        \$this->{$name}Service = \${$name}Service;\n    }\n";

        $content = preg_replace($pattern, $replacement, $content, 1);

        File::put($path, $content);
    }

}
