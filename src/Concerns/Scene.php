<?php

namespace CrCms\Repository\Concerns;

use Illuminate\Support\Arr;

trait Scene
{
    /**
     * @var array
     */
    protected $scenes = [];

    /**
     * current Scene
     *
     * @var string|null
     */
    protected $scene = null;

    /**
     * @param string $scene
     *
     * @return Scene
     */
    public function setScene(string $scene): self
    {
        $this->scene = $scene;

        return $this;
    }

    /**
     * @return string
     */
    public function getScene(): string
    {
        return $this->scene;
    }

    /**
     * @param string $key
     * @param array $scenes
     *
     * @return Scene
     */
    public function setScenes(string $key, array $scenes): self
    {
        $this->scenes[$key] = $scenes;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getScenes(): array
    {
        return $this->scenes;
    }

    /**
     * @param string $key
     * @param array $default
     *
     * @return array
     */
    public function getScenesByKey(string $key, array $default = []): array
    {
        return $this->scenes[$key] ?? $default;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function sceneFilter(array $data): array
    {
        if (empty($this->scene)) {
            return $data;
        }

        return Arr::only($data,$this->scenes[$this->scene] ?? []);
    }
}
