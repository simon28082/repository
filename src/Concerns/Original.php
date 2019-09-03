<?php

namespace CrCms\Repository\Concerns;

trait Original
{
    /**
     * @var array
     */
    protected $original = [];

    /**
     * @param array $data
     *
     * @return Original
     */
    public function setOriginal(array $data): self
    {
        $this->original = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getOriginal(): array
    {
        return $this->original;
    }
}
