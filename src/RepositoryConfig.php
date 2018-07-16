<?php

namespace CrCms\Repository;

/**
 * Class RepositoryConfig
 * @package CrCms\Repository
 */
class RepositoryConfig
{
    /**
     * @var
     */
    protected static $instance;

    /**
     * @var
     */
    protected $config;

    /**
     * RepositoryConfig constructor.
     * @param array $config
     */
    protected function __construct(array $config = [])
    {
        $this->mergeConfig($config);
    }

    /**
     * @param array $config
     * @return RepositoryConfig
     */
    public static function instance(array $config = [])
    {
        if (!static::$instance instanceof RepositoryConfig) {
            static::$instance = new static($config);
        }

        return static::$instance;
    }

    /**
     * @param array $config
     * @return $this
     */
    public function mergeConfig(array $config)
    {
        $this->config = array_merge($this->defaultConfig(), $config);
        return $this;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return array
     */
    protected function defaultConfig(): array
    {
        return require __DIR__ . DIRECTORY_SEPARATOR . '../config/repository.php';
    }
}