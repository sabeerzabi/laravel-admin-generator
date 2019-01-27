<?php

namespace Sabeer\AdminGenerator\Commands;

use Illuminate\Console\Command;

class AdminFileCommand extends Command
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'File';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:file
    	{name : The name of the class}
        {--namespace= : The namespace class. Output strategy will follow this namespace}
        {--N|config-namespace= : choose Default Namespace}
        {--r|request= :  Store, Update, All (s,u,*)}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a admin full module generator';


    /**
     * Create a new controller creator command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $defaultNamespace = config('admin-generator.namespace');
        $namespace = $this->option('config-namespace');

        $model = '';
        $repository = '';
        $request = '';

        if(array_key_exists($namespace, $defaultNamespace)){
            $model = $defaultNamespace[$namespace]['model'];
            $repository = $defaultNamespace[$namespace]['repository'];
            $request = $defaultNamespace[$namespace]['repository'];
        }

        $this->call('admin:model', ['name' => $model.$this->argument('name'), '--namespace' => $this->option('namespace')]);
        $this->call('admin:repository', ['name' => $repository.$this->argument('name')]);
        $this->call('admin:request', ['name' => $request.$this->argument('name'), '--request' => $this->option('request')]);
    }
}
