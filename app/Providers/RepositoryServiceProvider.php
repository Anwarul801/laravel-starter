<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-05 14:47:39
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-05 17:17:31
 * @Description: Innova IT
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
       $repoPath = app_path('Repositories');
        if (!is_dir($repoPath)) {
            return;
        }
          foreach (scandir($repoPath) as $file) {
            if (Str::endsWith($file, 'Repository.php')) {

                // Class name
                $class = 'App\\Repositories\\' . Str::replace('.php', '', $file);

                if (class_exists($class)) {
                    // Auto bind: repository bind to itself
                    $this->app->bind($class, $class);
                }
            }

        }
    }

    public function boot()
    {
        //
    }
}
