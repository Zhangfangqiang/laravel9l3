<?php

namespace App\Models\Traits;

Trait QueryBuilderBindable
{
    /**
     * 直接 use 引用就行, 如果绑定
     * protected $queryClass = \App\Http\Queries\xxx::class;
     * 重写路由路由模型绑定
     * @param $value
     * @return mixed
     */
    public function resolveRouteBinding($value)
    {
        $queryClass = property_exists($this, 'queryClass')
            ? $this->queryClass
            : '\\App\\Http\\Queries\\'.class_basename(self::class).'Query';

        if (!class_exists($queryClass)) {
            return parent::resolveRouteBinding($value);
        }

        return (new $queryClass($this))
            ->where($this->getRouteKeyName(), $value)
            ->first();
    }
}
