<?php

namespace CrCms\Repository;

use CrCms\Repository\Concerns\HasData;
use CrCms\Repository\Concerns\HasGuard;
use CrCms\Repository\Concerns\HasSceneGuard;
use CrCms\Repository\Contracts\QueryMagic;
use CrCms\Repository\Contracts\QueryRelate;

/**
 * Class AbstractMagic.
 */
abstract class AbstractMagic implements QueryMagic
{
    use HasData, HasGuard, HasSceneGuard;

    /**
     * AbstractMagic constructor.
     *
     * @param array  $data
     * @param string $scene
     */
    public function __construct(array $data = [], string $scene = '')
    {
        $this->setData($data);
        $this->setCurrentScene($scene ? $scene : $this->currentScene);
    }

    /**
     * @param QueryRelate        $queryRelate
     * @param AbstractRepository $repository
     *
     * @return QueryRelate
     */
    public function magic(QueryRelate $queryRelate, AbstractRepository $repository): QueryRelate
    {
        // 版本兼容，下个大版本直接删除
        if (method_exists($this, 'magicSearch')) {
            return $this->magicSearch($this->data, $queryRelate);
        }

        return $this->dispatch($this->filter($this->guardResult($this->data)), $queryRelate);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function guardResult(array $data): array
    {
        // guard 优先
        if (!empty($this->guard)) {
            return $this->guard($data);
        }

        $guard = $this->getSceneGuard($this->currentScene);

        return empty($guard) ? $data : $this->guardFilter($data, $guard);
    }

    /**
     * @param array       $data
     * @param QueryRelate $queryRelate
     *
     * @return QueryRelate
     */
    protected function dispatch(array $data, QueryRelate $queryRelate): QueryRelate
    {
        foreach ($data as $key => $item) {
            $item = is_array($item) ? $item : trim($item);
            $method = 'by'.studly_case($key);

            if (method_exists($this, $method)) {
                $queryRelate = call_user_func_array([$this, $method], [$queryRelate, $item]);
            }
        }

        return $queryRelate;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function filter(array $data): array
    {
        return array_filter($data, function ($item) {
            if (is_array($item)) {
                return $this->filter($item);
            }
            $item = trim($item);
            //防止字符串'0'
            return is_numeric($item) || !empty($item);
        });
    }
}
