<?php

namespace CrCms\Repository\Tests;

use CrCms\Repository\Console\Commands\Creator\MagicCreator;
use Tests\TestCase;

/**
 * Class MagicGenerateTest.
 */
class MagicGenerateTest extends TestCase
{
    public function testCreated()
    {
        $magicPath = base_path('abs/magic');
        config(['repository.magic_path' => $magicPath]);
        $this->app->make(MagicCreator::class)->create('TestMagic');

        sleep(2);

        $result = file_exists($magicPath) &&
            file_exists($magicPath.'/TestMagic.php');

        $this->assertEquals(true, $result);
    }

    public function testPath()
    {
        $magicPath = resource_path('magic');

        $this->app->make(MagicCreator::class)->create('Test2Magic', $magicPath);

        $result = file_exists($magicPath) &&
            file_exists($magicPath.'/Test2Magic.php');

        $this->assertEquals(true, $result);
    }

    public function testNamespace()
    {
        $this->app->make(MagicCreator::class)->create('Test2Magic', '', 'Test\Magic');

        $content = file_get_contents(config('repository.magic_path').'/Test2Magic.php');

        $this->assertEquals(true, strpos($content, 'Test\Magic'));
    }
}
