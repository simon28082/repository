<?php

namespace CrCms\Repository\Concerns;

/**
 * Class HasData
 * @package CrCms\Repository\Concerns
 */
trait HasData
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     * @return HasData
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}