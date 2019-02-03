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
        {--b|blade= :  Index, Create, Edit, Show (i,c,e,s,*)}
        {--f|file= :  Model, Repository, Request, All (md,rp,rq,cn,vw,*)}
        {--e|file-except= :  Model, Repository, Request, All (md,rp,rq,cn,vw)}
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
        $files = ['md', 'rp', 'rq', 'cn', 'vw'];

        /* Only include */
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

        /* Except */
        $file = $this->option('file-except');

        if(!empty($file)){
            $inpFile= explode(',', $file);
            $fl = array();
            foreach($files as $keyFile=>$file){
                if(in_array($file, $inpFile)){
                    unset($files[$keyFile]);
                }
            }
        }

        $model = '';
        $repository = '';
        $request = '';
        $controller = '';
        $view = '';

        if(array_key_exists($namespace, $defaultNamespace)){
            $model = $defaultNamespace[$namespace]['model'];
            $repository = $defaultNamespace[$namespace]['repository'];
            $request = $defaultNamespace[$namespace]['repository'];
            $controller = $defaultNamespace[$namespace]['controller'];
        }
        if(in_array('md', $files))
            $this->call('admin:model', ['name' => $model.$this->argument('name'), '--namespace' => $this->option('namespace')]);

        if(in_array('rp', $files))
            $this->call('admin:repository', ['name' => $repository.$this->argument('name')]);

        if(in_array('rq', $files))
            $this->call('admin:request', ['name' => $request.$this->argument('name'), '--request' => $this->option('request')]);

        if(in_array('cn', $files))
            $this->call('admin:controller', ['name' => $controller.$this->argument('name')]);

        if(in_array('vw', $files))
            $this->call('admin:view', ['name' => $controller.$this->argument('name'), '--blade'=>$this->option('request')]);
    }
}
