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
}
