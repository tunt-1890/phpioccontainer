<?php

namespace Illuminate\Tests\Container;

use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testClosureResolution()
    {
        $container = new Container;
        $container->bind('name', function () {
            return 'You';
        });
        $this->assertSame('You', $container->make('name'));
    }
    public function testAutoConcreteResolution()
    {
        $container = new Container;
        $this->assertInstanceOf(ContainerConcreteStub::class, $container->make(ContainerConcreteStub::class));
    }

    public function testAbstractToConcreteResolution()
    {
        $container = new Container;
        $container->bind(IContainerContractStub::class, ContainerImplementationStub::class);
        $class = $container->make(ContainerDependentStub::class);
        $this->assertInstanceOf(ContainerImplementationStub::class, $class->impl);
    }
}

interface IContainerContractStub
{
    //
}
class ContainerImplementationStub implements IContainerContractStub
{
    //
}
class ContainerDependentStub
{
    public $impl;

    public function __construct(IContainerContractStub $impl)
    {
        $this->impl = $impl;
    }
}
class ContainerConcreteStub
{
    //
}
