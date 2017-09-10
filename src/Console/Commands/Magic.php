<?php

namespace CrCms\Repository\Console\Commands;

use CrCms\Repository\Console\Commands\Creator\MagicCreator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Magic
 * @package CrCms\Repository\Console\Commands
 */
class Magic extends Command
{
    /**
     * @var string
     */
    protected $name = 'make:magic';

    /**
     * @var string
     */
    protected $description = 'Create a new magic class';

    /**
     * @var MagicCreator|null
     */
    protected $creator = null;

    /**
     * Magic constructor.
     * @param MagicCreator $creator
     */
    public function __construct(MagicCreator $creator)
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
//        $options = $this->options();

        $this->creator->create($arguments['magic']);

        //update composer autoload
        app('composer')->dumpAutoloads();

        $this->info("Successfully created the magic class");
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['magic', InputArgument::REQUIRED, 'The magic name.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['repository', '', InputOption::VALUE_OPTIONAL, 'The repository name.', ''],
        ];
    }
}
