<?php

namespace CrCms\Repository\Concerns;

trait HasGuard
{
    /**
     * @var array
     */
    protected $guard = [];

    /**
     * @param array $guard
     *
     * @return HasGuard
     */
    public function setGuard(array $guard): self
    {
        $this->guard = $guard;

        return $this;
    }

    /**
     * @return array
     */
    public function getGuard(): array
    {
        return $this->guard;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function guard(array $data): array
    {
        if (empty($this->guard)) {
            return $this->guard;
        }

        return array_filter($data, function ($key) {
            return in_array($key, $this->guard, true);
        }, ARRAY_FILTER_USE_KEY);
    }
}
