<?php

namespace Illuminate\Contracts\Container;

interface Container
{
    public function bind($abstract, $concrete = null);
    public function make($abstract);
}
