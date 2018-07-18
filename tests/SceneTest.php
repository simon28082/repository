<?php

namespace CrCms\Repository\Tests;

use Tests\TestCase;

/**
 * Class SceneTest
 * @package CrCms\Repository\Tests
 */
class SceneTest extends TestCase
{
    /**
     * @var Scene
     */
    protected $scene;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->scene = new Scene();
    }

    public function testSetScenes()
    {
        $this->scene->setScenes([
            $this->scene->getCurrentScene() => ['id', 'name', 'age'],
            'name' => ['name'],
            'age' => ['age']
        ]);

        $this->assertArrayHasKey($this->scene->getCurrentScene(), $this->scene->getScenes());
        $this->assertArrayHasKey('name', $this->scene->getScenes());
        $this->assertArrayHasKey('age', $this->scene->getScenes());

        $this->assertEquals(count($this->scene->getScenes()), 3);
        $this->assertEquals(count($this->scene->getScenes(), true), 8);
    }

    public function testSceneGuard()
    {
        $this->scene->setSceneGuard('test', ['a', 'b', 'c']);
        $guard = $this->scene->getSceneGuard('test');
        $this->assertEquals(count($guard), 3);

        $this->scene->addSceneGuard('test','d');
        $this->assertEquals(count($guard), 4);
        $this->assertArrayHasKey('d',$this->scene->getSceneGuard('test'));
    }

    public function testSceneGuardFilter()
    {
        $array = $this->scene->sceneGuard(['id' => 1, 'key1' => 'v1', 'k2' => 'v2'], $this->scene->getCurrentScene());

        $this->assertEquals(count($array),1);
        $this->assertArrayHasKey('id',$array);
    }

    public function testSetCurrentScene()
    {
        $this->scene->setCurrentScene('test');

        $this->assertEquals($this->scene->getCurrentScene(),'test');
    }

}