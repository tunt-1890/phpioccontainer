<?php

namespace Illuminate\Container;

use Illuminate\Contracts\Container\Container as ContainerContract;

class Container implements ContainerContract
{
    protected $bindings = [];

    public function bind($abstract, $concrete = null)
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function make($abstract)
    {
        $concrete = $this->getConcrete($abstract);
        return $concrete();
    }

    protected function getConcrete($abstract)
    {
        return $this->bindings[$abstract];
    }
}
