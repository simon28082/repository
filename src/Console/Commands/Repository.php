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
     * @var RepositoryCreator
     */
    protected $creator;

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
     * @throws \Exception
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

        $this->creator->create($arguments['repository'], $options['model'], $options['path']);

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
            ['model', null, InputOption::VALUE_REQUIRED, 'The model name.',],
            ['path', null, InputOption::VALUE_OPTIONAL, 'File storage location.', config('repository.repository_path')],
        ];
    }
}
