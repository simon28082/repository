<?php

namespace CrCms\Repository\Console\Commands;

use CrCms\Repository\Console\Commands\Creator\RepositoryCreator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Repository
 * @package CrCms\Repository\Console\Commands
 */
class Repository extends Command
{
    /**
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * @var RepositoryCreator|null
     */
    protected $creator = null;

    /**
     * Repository constructor.
     * @param RepositoryCreator $creator
     */
    public function __construct(RepositoryCreator $creator)
    {
        parent::__construct();
        $this->creator = $creator;
    }

    /**
     *
     */
    public function handle()
    {
        //
        $arguments = $this->arguments();
        $options = $this->options();

        if (empty($options['model'])) {
            $this->error('The model option not null');
            exit();
        }

        $this->creator->create($arguments['repository'], $options['model'], $options['path'], $options['namespace']);

        //update composer autoload
        $this->getLaravel()->make('composer')->dumpAutoloads();

        $this->info("Successfully created the repository class");
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['repository', InputArgument::REQUIRED, 'The repository name.']
        ];
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['model', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_REQUIRED, 'The model name.'],
            ['path', $this->app->make('config')->get('repository.repository_path'), InputOption::VALUE_OPTIONAL, 'File storage location.', ''],
            ['namespace', $this->app->make('config')->get('repository.repository_namespace'), InputOption::VALUE_OPTIONAL, 'File loaded namespace.', ''],
        ];
    }
}
