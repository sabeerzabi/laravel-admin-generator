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
    	{--N|namespace= : The namespace class. Output strategy will follow this namespace}';

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
        $this->call('admin:model', ['name' => $this->argument('name'), '--namespace' => $this->option('namespace')]);
        $this->call('admin:repository', ['name' => $this->argument('name')]);
        $this->call('admin:request', ['name' => $this->argument('name'), '--request' => $this->option('namespace')]);
    }
}
