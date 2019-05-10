<?php

namespace CrCms\Repository\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Magic.
 */
class Magic extends GeneratorCommand
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
     * @var string
     */
    protected $type = 'Magic';

    /**
     * @return string
     */
    protected function getStub(): string 
    {
        return __DIR__.'/stubs/magic.stub';
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the magic already exists'],
        ];
    }
}
