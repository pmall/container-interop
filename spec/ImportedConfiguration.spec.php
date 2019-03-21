<?php

use function Eloquent\Phony\Kahlan\mock;

use Interop\Container\ServiceProviderInterface;

use Quanta\Container\MergedConfiguration;
use Quanta\Container\ImportedConfiguration;
use Quanta\Container\ConfigurationSourceInterface;
use Quanta\Container\ServiceProviderConfiguration;

require_once __DIR__ . '/.test/classes.php';

describe('ImportedConfiguration', function () {

    context('when the collection is an array', function () {

        context('when the array is empty', function () {

            beforeEach(function () {

                $this->source = new ImportedConfiguration([]);

            });

            it('should implement ConfigurationSourceInterface', function () {

                expect($this->source)->toBeAnInstanceOf(ConfigurationSourceInterface::class);

            });

            describe('->configuration()', function () {

                it('should return an empty merged configuration', function () {

                    $test = $this->source->configuration();

                    expect($test)->toEqual(new MergedConfiguration);

                });

            });

        });

        context('when the array is not empty', function () {

            beforeEach(function () {

                $this->source = new ImportedConfiguration([
                    1,
                    new class {},
                    Test\TestServiceProvider1::class,
                    StdClass::class,
                    new Test\TestServiceProvider2,
                    [],
                    Test\TestServiceProvider3::class,
                    null,
                ]);

            });

            it('should implement ConfigurationSourceInterface', function () {

                expect($this->source)->toBeAnInstanceOf(ConfigurationSourceInterface::class);

            });

            describe('->configuration()', function () {

                it('should return a merged configuration', function () {

                    $test = $this->source->configuration();

                    expect($test)->toEqual(new MergedConfiguration(...[
                        new ServiceProviderConfiguration(new Test\TestServiceProvider1),
                        new ServiceProviderConfiguration(new Test\TestServiceProvider2),
                        new ServiceProviderConfiguration(new Test\TestServiceProvider3),
                    ]));

                });

            });

        });

    });

    context('when the collection is an iterator', function () {

        context('when the array is empty', function () {

            beforeEach(function () {

                $this->source = new ImportedConfiguration(new ArrayIterator([]));

            });

            it('should implement ConfigurationSourceInterface', function () {

                expect($this->source)->toBeAnInstanceOf(ConfigurationSourceInterface::class);

            });

            describe('->configuration()', function () {

                it('should return an empty merged configuration', function () {

                    $test = $this->source->configuration();

                    expect($test)->toEqual(new MergedConfiguration);

                });

            });

        });

        context('when the array is not empty', function () {

            beforeEach(function () {

                $this->source = new ImportedConfiguration(new ArrayIterator([
                    1,
                    new class {},
                    Test\TestServiceProvider1::class,
                    StdClass::class,
                    new Test\TestServiceProvider2,
                    [],
                    Test\TestServiceProvider3::class,
                    null,
                ]));

            });

            it('should implement ConfigurationSourceInterface', function () {

                expect($this->source)->toBeAnInstanceOf(ConfigurationSourceInterface::class);

            });

            describe('->configuration()', function () {

                it('should return a merged configuration', function () {

                    $test = $this->source->configuration();

                    expect($test)->toEqual(new MergedConfiguration(...[
                        new ServiceProviderConfiguration(new Test\TestServiceProvider1),
                        new ServiceProviderConfiguration(new Test\TestServiceProvider2),
                        new ServiceProviderConfiguration(new Test\TestServiceProvider3),
                    ]));

                });

            });

        });

    });

    context('when the collection is a Traversable', function () {

        context('when the array is empty', function () {

            beforeEach(function () {

                $this->source = new ImportedConfiguration(new class implements IteratorAggregate
                {
                    public function getIterator()
                    {
                        return new ArrayIterator([]);
                    }
                });

            });

            it('should implement ConfigurationSourceInterface', function () {

                expect($this->source)->toBeAnInstanceOf(ConfigurationSourceInterface::class);

            });

            describe('->configuration()', function () {

                it('should return an empty merged configuration', function () {

                    $test = $this->source->configuration();

                    expect($test)->toEqual(new MergedConfiguration);

                });

            });

        });

        context('when the array is not empty', function () {

            beforeEach(function () {

                $this->source = new ImportedConfiguration(new class implements IteratorAggregate
                {
                    public function getIterator()
                    {
                        return new ArrayIterator([
                            1,
                            new class {},
                            Test\TestServiceProvider1::class,
                            StdClass::class,
                            new Test\TestServiceProvider2,
                            [],
                            Test\TestServiceProvider3::class,
                            null,
                        ]);
                    }
                });

            });

            it('should implement ConfigurationSourceInterface', function () {

                expect($this->source)->toBeAnInstanceOf(ConfigurationSourceInterface::class);

            });

            describe('->configuration()', function () {

                it('should return a merged configuration', function () {

                    $test = $this->source->configuration();

                    expect($test)->toEqual(new MergedConfiguration(...[
                        new ServiceProviderConfiguration(new Test\TestServiceProvider1),
                        new ServiceProviderConfiguration(new Test\TestServiceProvider2),
                        new ServiceProviderConfiguration(new Test\TestServiceProvider3),
                    ]));

                });

            });

        });

    });

});
