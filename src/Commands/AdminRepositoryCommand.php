<?php

namespace Sabeer\AdminGenerator\Commands;

class AdminRepositoryCommand extends AdminGeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

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
    protected $signature = 'admin:repository
    	{name : The model class name.}
    	{--d|disable-softdelete : Disable softdelete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

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
        return __DIR__.'/stubs/repository.stub';
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

        $model = basename($this->getNameInput());
        $model_path = str_replace("/", "\\", $this->argument('name'));
        $class = $this->getConvertClassName($name);
        $stub = $this->compileStub([
            'model' => $model,
            'model_path' => $model_path,
            'namespace' => $this->getNamespace($name),
            'class' => $class,
            'contract' => basename($this->getNameInput()).'Contract'
        ]);

        $stub = $this->compileMethods($stub, $this->getMethods());

        $this->files->put($path, $stub);

        $this->info($this->type.' created successfully.');

        /* contract create */
        $this->call('admin:contract', ['name' => $this->argument('name')]);
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
        return basename($this->getNameInput()) . 'Repository';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories' .'\\' .dirname($this->argument('name'));
    }
}
