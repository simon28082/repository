<?php

namespace CrCms\Repository\Concerns;

use Illuminate\Support\Arr;

/**
 * Class HasData.
 */
trait HasData
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     *
     * @return HasData
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData(?string $key = null, $default = null)
    {
        return $key ? ($this->data[$key] ?? $default) : $this->data;
    }

    /**
     * @param array|string $key
     * @param null         $value
     *
     * @return HasData
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
