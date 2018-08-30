<?php

namespace CrCms\Repository;

use CrCms\Event\Dispatcher;
use CrCms\Repository\Console\Commands\Magic;
use CrCms\Repository\Console\Commands\Repository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository as Config;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * @var string
     */
    protected $namespaceName = 'repository';

    /**
     * @var string
     */
    protected $packagePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

    /**
     * @return void
     */
    public function boot()
    {
        //move config path
        $this->publishes([
            $this->packagePath . 'config' => config_path(),
        ]);
    }

    /**
     * @return void
     */
    public function register()
    {
        //merge config
        $configFile = $this->packagePath . "config/{$this->namespaceName}.php";
        if (file_exists($configFile)) $this->mergeConfigFrom($configFile, $this->namespaceName);

        //bind commands
        $this->app->singleton('command.repository.make', Repository::class);
        $this->app->singleton('command.magic.make', Magic::class);

        // Register commands
        $this->commands(['command.repository.make', 'command.magic.make']);

        //register dispatcher
        AbstractRepository::setDispatcher(new Dispatcher);
        AbstractRepository::events($this->app->make(Config::class)->get('repository.listener'));
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            'command.repository.make',
            'command.magic.make',
        ];
    }
}
