<?php

namespace Sabeer\AdminGenerator\Commands;

class AdminMethodCommand extends AdminGeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Method';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:method
    	{name : The name of the class}
    	{--N|namespace= : The namespace class. Output strategy will follow this namespace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new method traits for model';

    /**
     * The methods available.
     *
     * @var array
     */
    protected function getMethods()
    {
        return [];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/method.stub';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();
    }

    /**
     * Get the intended name for class.
     *
     * @return string
     */
    protected function getClassName()
    {
        return basename($this->getNameInput()) . $this->type;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $this->option('namespace') ?? dirname($this->argument('name'));
        $slash = empty($namespace)?'':"\\";
        return $rootNamespace . '\Models' . '\\' . $namespace . $slash.'Traits' .  '\\' . $this->type;
    }
}
