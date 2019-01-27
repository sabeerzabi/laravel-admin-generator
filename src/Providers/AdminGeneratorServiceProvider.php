<?php

namespace Sabeer\AdminGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Sabeer\AdminGenerator\Commands\AdminModelCommand;
use Sabeer\AdminGenerator\Commands\AdminScopeCommand;
use Sabeer\AdminGenerator\Commands\AdminFileCommand;
use Sabeer\AdminGenerator\Commands\AdminMethodCommand;
use Sabeer\AdminGenerator\Commands\AdminContractCommand;
use Sabeer\AdminGenerator\Commands\AdminRequestCommand;
use Sabeer\AdminGenerator\Commands\AdminAttributeCommand;
use Sabeer\AdminGenerator\Commands\AdminRepositoryCommand;
use Sabeer\AdminGenerator\Commands\AdminRelationshipCommand;

class AdminGeneratorServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerGeneratorCommands();
        }

        $this->publishes([
            __DIR__.'/../config/admin-generator.php' => config_path('admin-generator.php')
        ], 'config');

    }

    public function register()
    {

    }


    /**
     * Register console commands.
     */
    protected function registerGeneratorCommands()
    {
        $this->commands([
            AdminModelCommand::class,
            AdminScopeCommand::class,
            AdminMethodCommand::class,
            AdminAttributeCommand::class,
            AdminRepositoryCommand::class,
            AdminRelationshipCommand::class,
            AdminContractCommand::class,
            AdminFileCommand::class,
            AdminRequestCommand::class,
        ]);
    }
}

