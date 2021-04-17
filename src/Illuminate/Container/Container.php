<?php

namespace Illuminate\Container;

use Illuminate\Contracts\Container\Container as ContainerContract;
use Closure;
use ReflectionClass;
use Exception;

class Container implements ContainerContract
{
    protected $bindings = [];

    public function bind($abstract, $concrete = null)
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function make($abstract)
    {
        return $this->resolve($abstract);
    }
    protected function resolve($abstract)
    {
        $concrete = $this->getConcrete($abstract);

        if (!$this->isBuildable($concrete, $abstract)) {
            throw new Exception("Target class [$concrete] is not buildable.");
        }

        $object = $this->build($concrete);
        return $object;
    }
    protected function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof Closure;
    }

    protected function getConcrete($abstract)
    {
        if (isset($this->bindings[$abstract])) {
            return $this->bindings[$abstract];
        }

        return $abstract;
    }
    public function build($concrete)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        $reflector = new ReflectionClass($concrete);
        return $reflector->newInstanceArgs();
    }
}
