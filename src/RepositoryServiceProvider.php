<?php
namespace CrCms\Repository;

use CrCms\Repository\Console\Commands\Magic;
use CrCms\Repository\Console\Commands\Repository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @var string
     */
    protected $namespaceName = 'repository';

    /**
     * @var string
     */
    protected $packagePath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;


    /**
     *
     */
    public function boot()
    {
        //move config path
        $this->publishes([
            $this->packagePath.'config' => config_path(),
        ]);
    }


    /**
     *
     */
    public function register()
    {
        //bind commands
        $this->app->singleton('command.repository.make',Repository::class);
        $this->app->singleton('command.magic.make',Magic::class);

        // Register commands
        $this->commands(['command.repository.make', 'command.magic.make']);

        //merge config
        $configFile = $this->packagePath."config/{$this->namespaceName}.php";
        if (file_exists($configFile)) {
            $this->mergeConfigFrom($configFile, $this->namespaceName);
        }
    }


    public function provides()
    {
        return [
            'command.repository.make',
            'command.magic.make',
        ];
    }

}