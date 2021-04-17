<?php

namespace Illuminate\Container;

use Illuminate\Contracts\Container\Container as ContainerContract;
use Closure;
use ReflectionClass;
use Exception;
use ReflectionParameter;

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

        if ($this->isBuildable($concrete, $abstract)) {
            $object = $this->build($concrete);
        } else {
            $object = $this->make($concrete);
        }

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

        $constructor = $reflector->getConstructor();

        if(is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();

        $instances = $this->resolveDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    protected function resolveDependencies(array $dependencies)
    {
        $results = [];

        foreach ($dependencies as $dependency) {
            $result = $this->resolveClass($dependency);
            $results[] = $result;
        }

        return $results;
    }
    protected function resolveClass(ReflectionParameter $dependency)
    {
        $type = $dependency->getType();
        $className = $type->getName();
        return $this->make($className);
    }
}
