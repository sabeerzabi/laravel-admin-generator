<?php

namespace Sabeer\AdminGenerator\Commands;

class AdminViewCommand extends AdminGeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'View';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:view
    	{name : The name of the class}
        {--b|blade= :  Index, Create, Edit, Show (i,c,e,s,*)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create  new View Files with Index, Create, Edit,  and Show';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $views = ["i" => "index", "c" => "create", "e" => "edit", "s"=> "show"];
        $iViews = $this->option('blade');
        if(!empty($iViews)){
            $inpViews = explode(',', $iViews);
            if(!in_array('*', $inpViews)){
                $view = array();
                foreach($inpViews as $inpView){
                    if(array_key_exists($inpView, $views)){
                        $view[] = $views[$inpView];
                    }
                }
                $views = $view;
            }
        }
        $failedViews = 0;
        foreach($views as $view):
            $name = $this->parseName($view.".blade");
            $path = $this->getPath($name);

            if ($this->alreadyExists($view.".blade")) {
                $this->error($view.' '.$this->type.' already exists!');
                $failedViews ++;
                continue;
            }
            $this->makeDirectory($path);
            $this->files->put($path, '');

            $this->info(ucfirst($view).' '.$this->type.' created successfully.');
        endforeach;
    }

    /**
     * The methods available.
     *
     * @var array
     */
    protected function getMethods()
    {
        return '';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return '';
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
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return $this->argument('name');
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);

        if ($this->laravel->runningUnitTests()) {
    		return $this->laravel['config']['generator.path'].'/'.str_replace('\\', '/', $name).'.php';
    	}

        return dirname($this->laravel['path']).'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace. '\resources\views' .'\\' .dirname($this->argument('name'));
    }
}
