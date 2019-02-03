<?php

namespace Sabeer\AdminGenerator\Commands;

class AdminControllerCommand extends AdminGeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Default method of class being generated.
     *
     * @var array
     */
    protected $methods = ['all', 'paginated', 'find', 'create', 'update', 'delete', 'forceDelete', 'restore'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:controller
    	{name : The model class name.}
    	{--d|disable-softdelete : Disable softdelete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The methods available.
     *
     * @var array
     */
    protected function getMethods()
    {
        return $this->option('disable-softdelete')
        	? array_diff($this->methods, ['forceDelete', 'restore'])
        	: $this->methods;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/controller.stub';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->parseName($this->getClassName());

        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getClassName())) {
            $this->error($this->type.' already exists!');
            return;
        }

        $this->makeDirectory($path);

        $contract = basename($this->getNameInput());
        $contract_path = str_replace("/", "\\", $this->argument('name'))."Contract";
        $class = $this->getConvertClassName($name);
        $stub = $this->compileStub([
            'contract' => $contract."Contract",
            'variable' => strtolower($contract),
            'contract_path' => $contract_path,
            'namespace' => $this->getNamespace($name),
            'class' => $class
        ]);

        $stub = $this->compileMethods($stub, $this->getMethods());

        $this->files->put($path, $stub);

        $this->info($this->type.' created successfully.');
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return $this->argument('name');
    }

    /**
     * Get the intended name for class.
     *
     * @return string
     */
    protected function getClassName()
    {
        return basename($this->getNameInput()) . 'Controller';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers' .'\\' .dirname($this->argument('name'));
    }
}
