<?php declare(strict_types=1);

namespace Test;

use Interop\Container\ServiceProviderInterface;

interface TestServiceProviderInterface extends ServiceProviderInterface
{
    //
}

abstract class TestAbstractServiceProvider implements ServiceProviderInterface
{
    abstract public function getFactories();
    abstract public function getExtensions();
}

final class TestServiceProvider1 implements ServiceProviderInterface
{
    public function getFactories()
    {
        //
    }

    public function getExtensions()
    {
        //
    }
}

final class TestServiceProvider2 implements ServiceProviderInterface
{
    public function getFactories()
    {
        //
    }

    public function getExtensions()
    {
        //
    }
}

final class TestServiceProvider3 implements ServiceProviderInterface
{
    public function getFactories()
    {
        //
    }

    public function getExtensions()
    {
        //
    }
}
