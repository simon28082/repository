<?php
namespace CrCms\Repository\Console\Commands\Creator;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;

/**
 * Class MagicCreator
 * @package CrCms\Repository\Console\Commands\Creator
 */
class MagicCreator
{

    /**
     * @var Filesystem|null
     */
    protected $fileSystem = null;

    /**
     * @var Config|null
     */
    protected $config = null;

    /**
     * @var string
     */
    protected $magic = '';

    /**
     * @var string
     */
    protected $magicNamespace = '';

    /**
     * @var string
     */
    protected $repository = '';


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
     * @return MagicCreator
     */
    public function setMagic(string $magic) : self
    {
        //
        $repository = studly_case($magic);

        //auto include namespace
        if (strpos($magic,'\\')) {
            $this->magicNamespace = str_replace(strrchr($magic,'\\'),'',$magic);
            $magic = class_basename($magic);
        }

        $this->magic = $magic;

        return $this;
    }


    /**
     * @param string $repository
     * @return MagicCreator
     */
    public function setRepository(string $repository) : self
    {
        //$this->repository = $repository ? : 'CrCms\Repository\Contracts\Repository';
        $this->repository = 'CrCms\Repository\Contracts\Repository';
        return $this;
    }


    /**
     * @param string $magic
     */
    public function create(string $magic,string $repository = '')
    {
        //set and format arguments
        $this->setMagic($magic);
        $this->setRepository($repository);

        if ($this->checkFileExists()) {
            throw new \Exception('magic file is exists');
        }

        //create directory
        $this->createDirectory();

        //get stub file content
        $content = $this->getFormatStubFileContent();

        //write file
        $this->writeStubFile($content);
    }


    /**
     *
     */
    protected function createDirectory()
    {
        $repositoryDirectory = $this->getMagicDirectoryPath();

        if (!$this->fileSystem->isDirectory($repositoryDirectory)) {
            $this->fileSystem->makeDirectory($repositoryDirectory);
        }
    }

    /**
     * @return string
     */
    protected function getMagicDirectoryPath() : string
    {
        return $this->magicNamespace ?
            str_replace('\\','/',$this->magicNamespace) :
            $this->config->get('repository.magic_path');
    }


    /**
     * @return string
     */
    protected function getRepositoryPath() : string
    {
        return $this->getMagicDirectoryPath().'/'.$this->magic.'.php';
    }


    /**
     * @return string
     */
    protected function getStubFilePath() : string
    {
        return __DIR__.'../../../../../resource/stubs/magic.stub';
    }


    /**
     * @return string
     */
    protected function getStubFileContent() : string
    {
        return $this->fileSystem->get($this->getStubFilePath());
    }


    /**
     * @return string
     */
    protected function getFormatStubFileContent() : string
    {
        $magicNamespace = empty($this->magicNamespace) ?
            $this->config->get('repository.magic_namespace') :
            $this->magicNamespace;

        return str_replace([
            'magic_namespace',
            'magic_class',
            'repository_namespace',
        ],[
            $magicNamespace,
            $this->magic,
            $this->repository
        ],$this->getStubFileContent());
    }


    /**
     * @param string $content
     */
    protected function writeStubFile(string $content)
    {
        $this->fileSystem->put($this->getRepositoryPath(),$content);
    }


    /**
     * @return bool
     */
    protected function checkFileExists() : bool
    {
        return $this->fileSystem->exists($this->getRepositoryPath());
    }

}

