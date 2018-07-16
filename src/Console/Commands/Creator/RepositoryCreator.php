<?php

namespace CrCms\Repository\Console\Commands\Creator;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;

/**
 * Class RepositoryCreator
 * @package CrCms\Repository\Console\Commands\Creator
 */
class RepositoryCreator
{
//    /**
//     * @var Filesystem
//     */
//    protected $fileSystem;
//
//    /**
//     * @var Config
//     */
//    protected $config;
//
//    /**
//     * @var string
//     */
//    protected $repository = '';
//
//    /**
//     * @var string
//     */
//    protected $repositoryNameSpace = '';
//
//    /**
//     * @var string
//     */
//    protected $model = '';
//
//    /**
//     * RepositoryCreator constructor.
//     * @param Filesystem $filesystem
//     * @param Config $config
//     */
//    public function __construct(Filesystem $filesystem, Config $config)
//    {
//        $this->fileSystem = $filesystem;
//        $this->config = $config;
//    }
//
//    /**
//     * @param string $repository
//     * @return RepositoryCreator
//     */
//    public function setRepository(string $repository): self
//    {
//        //
//        $repository = studly_case($repository);
//
//        //auto include namespace
//        if (strpos($repository, '\\')) {
//            $this->repositoryNameSpace = str_replace(strrchr($repository, '\\'), '', $repository);
//            $repository = class_basename($repository);
//        }
//
//        $this->repository = $repository;
//
//        return $this;
//    }
//
//    /**
//     * @param string $model
//     * @return RepositoryCreator
//     */
//    public function setModel(string $model): self
//    {
//        $model = studly_case($model);
//
//        $this->model = $model;
//
//        return $this;
//    }
//
//    /**
//     * @param string $repository
//     * @param string $model
//     */
//    public function create(string $repository, string $model = '')
//    {
//        //set and format arguments
//        $this->setRepository($repository);
//        $this->setModel($model);
//
//        if ($this->checkFileExists()) {
//            throw new \Exception('repository file is exists');
//        }
//
//        //create directory
//        $this->createDirectory();
//
//        //get stub file content
//        $content = $this->getFormatStubFileContent();
//
//        //write file
//        $this->writeStubFile($content);
//    }
//
//    /**
//     *
//     */
//    protected function createDirectory()
//    {
//        $repositoryDirectory = $this->getRepositoryDirectoryPath();
//
//        if (!$this->fileSystem->isDirectory($repositoryDirectory)) {
//            $this->fileSystem->makeDirectory($repositoryDirectory);
//        }
//    }
//
//    /**
//     * @return string
//     */
//    protected function getRepositoryDirectoryPath(): string
//    {
//        return $this->repositoryNameSpace ?
//            str_replace('\\', '/', $this->repositoryNameSpace) :
//            $this->config->get('repository.repository_path');
//    }
//
//    /**
//     * @return string
//     */
//    protected function getRepositoryPath(): string
//    {
//        return $this->getRepositoryDirectoryPath() . '/' . $this->repository . '.php';
//    }
//
//    /**
//     * @return string
//     */
//    protected function getStubFilePath(): string
//    {
//        return __DIR__ . '/../../../../resource/stubs/repository.stub';
//    }
//
//    /**
//     * @return string
//     */
//    protected function getStubFileContent(): string
//    {
//        return $this->fileSystem->get($this->getStubFilePath());
//    }
//
//    /**
//     * @return string
//     */
//    protected function getFormatStubFileContent(): string
//    {
//        $repositoryNamespace = empty($this->repositoryNameSpace) ?
//            $this->config->get('repository.repository_namespace') :
//            $this->repositoryNameSpace;
//
//        return str_replace([
//            'repository_namespace',
//            'repository_class',
//            'model_path',
//        ], [
//            $repositoryNamespace,
//            $this->repository,
//            $this->model,
//        ], $this->getStubFileContent());
//    }
//
//    /**
//     * @param string $content
//     */
//    protected function writeStubFile(string $content)
//    {
//        $this->fileSystem->put($this->getRepositoryPath(), $content);
//    }
//
//    /**
//     * @return bool
//     */
//    protected function checkFileExists(): bool
//    {
//        return $this->fileSystem->exists($this->getRepositoryPath());
//    }

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

    public function create(string $repository, string $model, string $path = '', string $namespace = '')
    {
        $this->setNamespace($namespace);

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
     * @param string $namespace
     */
    protected function setNamespace(string $namespace)
    {
        $this->namespace = $namespace ? $namespace : $this->config->get('repository.repository_namespace');
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

    /**
     *
     */
    protected function autoCreateDirectory()
    {
        $directory = $this->getRepositoryDirectoryPath();

        if (!$this->fileSystem->isDirectory($directory)) {
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
        return __DIR__ . '/../../../../resource/stubs/repository.stub';
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
        return $this->getRepositoryDirectoryPath() . '/' . $this->repository . '.php';
    }
}

