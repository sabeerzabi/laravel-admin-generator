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
        {--f|file= :  Model, Repository, Request, All (md,rp,rq,*)}
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
        $files = ['md', 'rp', 'rq'];
        $file = $this->option('file');

        if(!empty($file)){
            $inpFile= explode(',', $file);
            if(!in_array('*', $inpFile)){
                $fl = array();
                foreach($inpFile as $inpFl){
                    if(in_array($inpFl, $files)){
                        $fl[] = $inpFl;
                    }
                }
                $files = $fl;
            }
        }

        $model = '';
        $repository = '';
        $request = '';

        if(array_key_exists($namespace, $defaultNamespace)){
            $model = $defaultNamespace[$namespace]['model'];
            $repository = $defaultNamespace[$namespace]['repository'];
            $request = $defaultNamespace[$namespace]['repository'];
        }
        if(in_array('md', $files))
            $this->call('admin:model', ['name' => $model.$this->argument('name'), '--namespace' => $this->option('namespace')]);

        if(in_array('rp', $files))
            $this->call('admin:repository', ['name' => $repository.$this->argument('name')]);

        if(in_array('rq', $files))
            $this->call('admin:request', ['name' => $request.$this->argument('name'), '--request' => $this->option('request')]);
    }
}
