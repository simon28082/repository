<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Repository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a new repository class';

    /**
     * Create a new command instance.
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
        //
//        $arguments = $this->arguments();
//        dd($arguments);

        $repository = $this->argument('repository');

        dd($repository);
    }


    protected function createRepository()
    {

        $repository = studly_case($this->argument('repository'));

        $repository = <<<string
<?php
namespace App\Repositories\{$repository}
repository
string;
    }
}
