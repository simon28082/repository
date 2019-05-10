<?php

namespace CrCms\Repository\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Repository.
 */
class Repository extends GeneratorCommand
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
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        $replace = $this->buildModelReplacements($replace);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @param array $replace
     *
     * @return array
     */
    protected function buildModelReplacements(array $replace = [])
    {
        $modelClass = $this->parseModel($this->option('model'));

        if (!class_exists($modelClass)) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', ['name' => $modelClass]);
            }
        }

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            'DummyModelClass'     => class_basename($modelClass),
            'DummyModelVariable'  => lcfirst(class_basename($modelClass)),
        ]);
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param string $model
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (!Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $model = $rootNamespace.$model;
        }

        return $model;
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/repository.stub';
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The model name.'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the repository already exists'],
        ];
    }
}
