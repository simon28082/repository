<?php

namespace CrCms\Repository\Console\Commands\Creator;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class RepositoryCreator.
 */
class RepositoryCreator
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $repository = '';

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $model;

    public function __construct(Filesystem $filesystem, Config $config)
    {
        $this->fileSystem = $filesystem;
        $this->config = $config;
    }

    /**
     * @param string $repository
     * @param string $model
     * @param string $path
     *
     * @throws Exception
     */
    public function create(string $repository, string $model, string $path = '')
    {
        $this->setNamespace($repository);

        $this->setModel($model);

        $this->setRepository($repository);

        $this->setPath($path);

        //check file exists
        if ($this->fileSystem->exists($this->repositoryFilePath())) {
            throw new Exception("File {$this->repositoryFilePath()} exists");
        }

        //create directory
        $this->autoCreateDirectory();

        //write file
        $this->writeStubFile($this->getFormatStubFileContent());
    }

    /**
     * @param string $repository
     */
    protected function setNamespace(string $repository)
    {
        $this->namespace = strpos($repository, '\\') ?
            str_replace(strrchr($repository, '\\'), '', $repository) :
            $this->config->get('repository.repository_namespace');
    }

    /**
     * @param string $model
     */
    protected function setModel(string $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $magic
     */
    protected function setRepository(string $repository)
    {
        $this->repository = class_basename($repository);
    }

    /**
     * @param string $path
     */
    protected function setPath(string $path)
    {
        $this->path = $path ? $path : $this->config->get('repository.repository_path');
    }

    protected function autoCreateDirectory()
    {
        $directory = $this->getRepositoryDirectoryPath();

        if (! $this->fileSystem->isDirectory($directory)) {
            $this->fileSystem->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * @return string
     */
    protected function getRepositoryDirectoryPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    protected function getStubFilePath(): string
    {
        return __DIR__.'/../../../../resource/stubs/repository.stub';
    }

    /**
     * @return string
     */
    protected function getStubFileContent(): string
    {
        return $this->fileSystem->get($this->getStubFilePath());
    }

    /**
     * @return string
     */
    protected function getFormatStubFileContent(): string
    {
        return str_replace([
            'repository_namespace',
            'repository_class',
            'model_namespace',
            'model_class',
        ], [
            $this->namespace,
            $this->repository,
            $this->model,
            class_basename($this->model),
        ], $this->getStubFileContent());
    }

    /**
     * @param string $content
     */
    protected function writeStubFile(string $content)
    {
        $this->fileSystem->put($this->repositoryFilePath(), $content);
    }

    /**
     * @return string
     */
    protected function repositoryFilePath(): string
    {
        return $this->getRepositoryDirectoryPath().'/'.$this->repository.'.php';
    }
}
