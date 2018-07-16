<?php

namespace CrCms\Repository\Console\Commands\Creator;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Exception;
use Illuminate\Support\Str;

/**
 * Class MagicCreator
 * @package CrCms\Repository\Console\Commands\Creator
 */
class MagicCreator
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
    protected $magic = '';

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var string
     */
    protected $path;

    /**
     * MagicCreator constructor.
     * @param Filesystem $filesystem
     * @param Config $config
     */
    public function __construct(Filesystem $filesystem, Config $config)
    {
        $this->fileSystem = $filesystem;
        $this->config = $config;
    }

    /**
     * @param string $magic
     * @param string $path
     * @param string $namespace
     * @throws Exception
     */
    public function create(string $magic, string $path = '', string $namespace = '')
    {
        $this->setNamespace($namespace);

        $this->setMagic($magic);

        $this->setPath($path);

        //check file exists
        if ($this->fileSystem->exists($this->magicFilePath())) {
            throw new Exception("File {$this->magicFilePath()} exists");
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
        //auto include namespace
        $this->namespace = $namespace ? $namespace : $this->config->get('repository.magic_namespace');
    }

    /**
     * @param string $magic
     */
    protected function setMagic(string $magic)
    {
        $this->magic = class_basename($magic);
    }

    /**
     * @param string $path
     */
    protected function setPath(string $path)
    {
        $this->path = $path ? $path : $this->config->get('repository.magic_path');
    }

    /**
     *
     */
    protected function autoCreateDirectory()
    {
        $magicDirectory = $this->getMagicDirectoryPath();

        if (!$this->fileSystem->isDirectory($magicDirectory)) {
            $this->fileSystem->makeDirectory($magicDirectory, 0755, true);
        }
    }

    /**
     * @return string
     */
    protected function getMagicDirectoryPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    protected function getStubFilePath(): string
    {
        return __DIR__ . '/../../../../resource/stubs/magic.stub';
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
            'magic_namespace',
            'magic_class',
        ], [
            $this->namespace,
            $this->magic,
        ], $this->getStubFileContent());
    }

    /**
     * @param string $content
     */
    protected function writeStubFile(string $content)
    {
        $this->fileSystem->put($this->magicFilePath(), $content);
    }

    /**
     * @return string
     */
    protected function magicFilePath(): string
    {
        return $this->getMagicDirectoryPath() . '/' . $this->magic . '.php';
    }
}

