<?php

namespace CrCms\Repository\Concerns;

use Illuminate\Support\Arr;

trait Data
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     *
     * @return Data
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function getDataByKey(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * @param array|string $key
     * @param null $value
     *
     * @return Data
     */
    public function addData($key, $value = null): self
    {
        is_array($key) ?
            $this->data = array_merge($this->data, $key) :
            $this->data[$key] = $value;

        return $this;
    }

    /**
     * @param string|array $key
     *
     * @return void
     */
    public function forgetData($key): void
    {
        Arr::forget($this->data, $key);
    }
}
