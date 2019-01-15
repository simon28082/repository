<?php

namespace CrCms\Repository\Concerns;

/**
 * Trait HasSceneGuard.
 */
trait HasSceneGuard
{
    /**
     * @var array
     */
    protected $scenes = [];

    /**
     * @var string
     */
    protected $currentScene = 'default';

    /**
     * @param string $scene
     *
     * @return HasSceneGuard
     */
    public function setCurrentScene(string $scene): self
    {
        $this->currentScene = $scene;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentScene(): string
    {
        return $this->currentScene;
    }

    /**
     * @param string $scene
     * @param array  $guard
     *
     * @return HasSceneGuard
     */
    public function setSceneGuard(string $scene, array $guard): self
    {
        $this->scenes[$scene] = $guard;

        return $this;
    }

    /**
     * @param string $scene
     *
     * @return array
     */
    public function getSceneGuard(string $scene): array
    {
        return $this->scenes[$scene] ?? [];
    }

    /**
     * @param string $scene
     * @param string $value
     *
     * @return HasSceneGuard
     */
    public function addSceneGuard(string $scene, string $value): self
    {
        $this->scenes[$scene] = $this->getSceneGuard($scene);

        if (!in_array($value, $this->scenes[$scene], true)) {
            $this->scenes[$scene][] = $value;
        }

        return $this;
    }

    /**
     * @param array $scenes
     *
     * @return HasSceneGuard
     */
    public function setScenes(array $scenes): self
    {
        $this->scenes = $scenes;

        return $this;
    }

    /**
     * @return array
     */
    public function getScenes(): array
    {
        return $this->scenes;
    }

    /**
     * @param array  $data
     * @param string $scene
     *
     * @return array
     */
    public function sceneGuard(array $data, string $scene): array
    {
        return $this->guardFilter($data, $this->getSceneGuard($scene));
    }

    /**
     * @param array $data
     * @param array $guard
     *
     * @return array
     */
    protected function guardFilter(array $data, array $guard): array
    {
        return empty($guard) ? [] :
            array_filter($data, function ($key) use ($guard) {
                return in_array($key, $guard, true);
            }, ARRAY_FILTER_USE_KEY);
    }
}
