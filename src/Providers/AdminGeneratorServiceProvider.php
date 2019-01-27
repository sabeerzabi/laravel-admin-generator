<?php

namespace Sabeer\AdminGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Sabeer\AdminGenerator\Commands\AppModelCommand;
use Sabeer\AdminGenerator\Commands\AppScopeCommand;
use Sabeer\AdminGenerator\Commands\AdminFileCommand;
use Sabeer\AdminGenerator\Commands\AppMethodCommand;
use Sabeer\AdminGenerator\Commands\AppContractCommand;
use Sabeer\AdminGenerator\Commands\AdminRequestCommand;
use Sabeer\AdminGenerator\Commands\AppAttributeCommand;
use Sabeer\AdminGenerator\Commands\AppRepositoryCommand;
use Sabeer\AdminGenerator\Commands\AppRelationshipCommand;

class AdminGeneratorServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerGeneratorCommands();
        }
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
            AppModelCommand::class,
            AppScopeCommand::class,
            AppMethodCommand::class,
            AppAttributeCommand::class,
            AppRepositoryCommand::class,
            AppRelationshipCommand::class,
            AppContractCommand::class,
            AdminFileCommand::class,
            AdminRequestCommand::class,
        ]);
    }
}

