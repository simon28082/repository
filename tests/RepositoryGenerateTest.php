<?php

namespace CrCms\Repository\Tests;

use CrCms\Repository\Console\Commands\Creator\RepositoryCreator;
use Tests\TestCase;

/**
 * Class RepositoryGenerateTest.
 */
class RepositoryGenerateTest extends TestCase
{
    public function testCreated()
    {
        $path = base_path('abs/repository');
        config(['repository.repository_path' => $path]);
        $this->app->make(RepositoryCreator::class)->create('TestRepository', 'App\Models\TestModel');

        sleep(2);

        $result = file_exists($path) &&
            file_exists($path.'/TestRepository.php');

        $this->assertEquals(true, $result);
    }

    public function testPath()
    {
        $path = resource_path('repository');

        $this->app->make(RepositoryCreator::class)->create('TestRepository', 'App\Models\TestModel', $path);

        $result = file_exists($path) &&
            file_exists($path.'/TestRepository.php');

        $this->assertEquals(true, $result);
    }

    public function testNamespace()
    {
        $this->app->make(RepositoryCreator::class)->create('TestRepository', 'App\Models\TestModel', '', 'Test\Repository');

        $content = file_get_contents(config('repository.repository_path').'/TestRepository.php');

        $this->assertEquals(true, strpos($content, 'Test\Repository'));
    }
}
