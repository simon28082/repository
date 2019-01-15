<?php

namespace CrCms\Repository\Console\Commands\Creator;

use Exception;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;

/**
 * Class MagicCreator.
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
     *
     * @param Filesystem $filesystem
     * @param Config     $config
     */
    public function __construct(Filesystem $filesystem, Config $config)
    {
        $this->fileSystem = $filesystem;
        $this->config = $config;
    }

    /**
     * @param string $magic
     * @param string $path
     *
     * @throws Exception
     */
    public function create(string $magic, string $path = '')
    {
        $this->setNamespace($magic);

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
     * @param string $magic
     */
    protected function setNamespace(string $magic)
    {
        $this->namespace = strpos($magic, '\\') ?
            str_replace(strrchr($magic, '\\'), '', $magic) :
            $this->config->get('repository.magic_namespace');
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
        return __DIR__.'/../../../../resource/stubs/magic.stub';
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
        return $this->getMagicDirectoryPath().'/'.$this->magic.'.php';
    }
}
