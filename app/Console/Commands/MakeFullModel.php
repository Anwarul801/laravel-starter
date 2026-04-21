<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFullModel extends Command
{
    protected $signature = 'make:fullmodel {name}';
    protected $description = 'Create Model, Migration, Controller, Repository, and Service with functions';

    public function handle()
    {
        $name = $this->argument('name');

        // --- 1. Model + Migration + Controller ---
        $this->call('make:model', [
            'name' => $name,
            '-m' => true,
        ]);

        $this->call('make:controller', [
            'name' => "{$name}Controller",
            '--resource' => true,
        ]);

        // --- 2. Create Repositories/Services directories if not exist ---
        $repositoriesPath = app_path('Repositories');
        $servicesPath = app_path('Services');

        if (!file_exists($repositoriesPath)) mkdir($repositoriesPath, 0755, true);
        if (!file_exists($servicesPath)) mkdir($servicesPath, 0755, true);

        // --- 3. Repository ---
        $repositoryPath = $repositoriesPath . "/{$name}Repository.php";
        if (!file_exists($repositoryPath)) {
            $repositoryTemplate = "<?php

namespace App\Repositories;

use App\Models\\$name;

class {$name}Repository
{
    protected \$model;

    public function __construct($name \$model)
    {
        \$this->model = \$model;
    }

    // CRUD Functions
    public function all()
    {
        return \$this->model->all();
    }

    public function find(\$id)
    {
        return \$this->model->find(\$id);
    }

    public function create(array \$data)
    {
        return \$this->model->create(\$data);
    }

    public function update(\$id, array \$data)
    {
        \$item = \$this->find(\$id);
        if (\$item) {
            \$item->update(\$data);
            return \$item;
        }
        return null;
    }

    public function delete(\$id)
    {
        \$item = \$this->find(\$id);
        if (\$item) {
            return \$item->delete();
        }
        return false;
    }
}
";
            file_put_contents($repositoryPath, $repositoryTemplate);
            $this->info("Repository created: {$repositoryPath}");
        }

        // --- 4. Service ---
        $servicePath = $servicesPath . "/{$name}Service.php";
        if (!file_exists($servicePath)) {
            $serviceTemplate = "<?php

namespace App\Services;

use App\Repositories\\{$name}Repository;

class {$name}Service
{
    protected \$repository;

    public function __construct({$name}Repository \$repository)
    {
        \$this->repository = \$repository;
    }

    // Service wrapper functions
    public function getAll()
    {
        return \$this->repository->all();
    }

    public function getById(\$id)
    {
        return \$this->repository->find(\$id);
    }

    public function create(array \$data)
    {
        return \$this->repository->create(\$data);
    }

    public function update(\$id, array \$data)
    {
        return \$this->repository->update(\$id, \$data);
    }

    public function delete(\$id)
    {
        return \$this->repository->delete(\$id);
    }
}
";
            file_put_contents($servicePath, $serviceTemplate);
            $this->info("Service created: {$servicePath}");
        }

        $this->info('All files created successfully!');
    }
}
