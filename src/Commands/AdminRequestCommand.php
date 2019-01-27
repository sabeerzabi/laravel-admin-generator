<?php

namespace Sabeer\AdminGenerator\Commands;

class AdminRequestCommand extends AdminGeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

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
    protected $signature = 'admin:request
    	{name : The model class name.}
        {--d|disable-softdelete : Disable softdelete}
        {--request= : Store, Update, All (s,u,*)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Request class';

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
        return __DIR__.'/stubs/request.stub';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $requests = ["s" => "Store", "u" => "Update"];
        $request = $this->option('request');
        if(!empty($request)){
            $inpRequest = explode(',', $request);
            if(!in_array('*', $inpRequest)){
                $req = array();
                foreach($inpRequest as $inpReq){
                    if(array_key_exists($inpReq, $requests)){
                        $req[] = $requests[$inpReq];
                    }
                }
                $requests = $req;
            }
        }
        $failedRequest = 0;
        foreach($requests as $request):
            $name = $this->parseName($request.$this->getClassName());

            $path = $this->getPath($name);

            if ($this->alreadyExists($request.$this->getClassName())) {
                $this->error($request.' '.$this->type.' already exists!');
                $failedRequest ++;
                continue;
            }

            $this->makeDirectory($path);

            $model = basename($this->getNameInput());
            $model_path = str_replace("/", "\\", $this->argument('name'));
            $class = $this->getConvertClassName($name);
            $stub = $this->compileStub([
                'class' => $class,
                'namespace' => $this->getNamespace($name),
            ]);

            $stub = $this->compileMethods($stub, $this->getMethods());

            $this->files->put($path, $stub);

            $this->info($request.' '.$this->type.' created successfully.');
        endforeach;

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
        return basename($this->getNameInput()) . 'Request';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Requests' .'\\' .dirname($this->argument('name'));
    }
}
