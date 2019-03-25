<?php

use function Eloquent\Phony\Kahlan\mock;

use Interop\Container\ServiceProviderInterface;

use Quanta\Container\FactoryMap;
use Quanta\Container\Configuration\ConfigurationEntry;
use Quanta\Container\Configuration\ConfigurationInterface;
use Quanta\Container\Configuration\ServiceProviderAdapter;
use Quanta\Container\Configuration\Passes\ExtensionPass;
use Quanta\Container\Configuration\Passes\MergedProcessingPass;

describe('ServiceProviderAdapter::instance()', function () {

    it('should return a new ServiceProviderAdapter using the given service provider', function () {

        $provider = mock(ServiceProviderInterface::class);

        $test = ServiceProviderAdapter::instance($provider->get());

        expect($test)->toEqual(new ServiceProviderAdapter($provider->get()));

    });

});

describe('ServiceProviderAdapter', function () {

    beforeEach(function () {

        $this->provider = mock(ServiceProviderInterface::class);

        $this->configuration = new ServiceProviderAdapter($this->provider->get());

    });

    it('should implement ConfigurationInterface', function () {

        expect($this->configuration)->toBeAnInstanceOf(ConfigurationInterface::class);

    });

    describe('->entry()', function () {

        context('when the provider ->getFactories() and ->getMethods() methods both return arrays of callables', function () {

            it('should return a configuration', function () {

                $this->provider->getFactories->returns([
                    'id1' => $factory1 = function () {},
                    'id2' => $factory2 = function () {},
                    'id3' => $factory3 = function () {},
                ]);

                $this->provider->getExtensions->returns([
                    'id1' => $extension1 = function () {},
                    'id2' => $extension2 = function () {},
                    'id3' => $extension3 = function () {},
                ]);

                $test = $this->configuration->entry();

                expect($test)->toEqual(new ConfigurationEntry(
                    new FactoryMap([
                        'id1' => $factory1,
                        'id2' => $factory2,
                        'id3' => $factory3,
                    ]),
                    new MergedProcessingPass(...[
                        new ExtensionPass('id1', $extension1),
                        new ExtensionPass('id2', $extension2),
                        new ExtensionPass('id3', $extension3),
                    ])
                ));

            });

        });

        context('when the provider ->getFactories() method does not return an array', function () {

            it('should throw an UnexpectedValueException', function () {

                $this->provider->getFactories->returns(1);
                $this->provider->getExtensions->returns([]);

                expect([$this->configuration, 'entry'])->toThrow(new UnexpectedValueException);

            });

        });

        context('when a value of the array returned by the provider ->getFactories() is not a callable', function () {

            it('should throw an UnexpectedValueException', function () {

                $this->provider->getFactories->returns([
                    'id1' => function () {},
                    'id2' => 1,
                    'id3' => function () {},
                ]);

                $this->provider->getExtensions->returns([]);

                expect([$this->configuration, 'entry'])->toThrow(new UnexpectedValueException);

            });

        });

        context('when the provider ->getExtensions() method does not return an array', function () {

            it('should throw an UnexpectedValueException', function () {

                $this->provider->getFactories->returns([]);
                $this->provider->getExtensions->returns(1);

                expect([$this->configuration, 'entry'])->toThrow(new UnexpectedValueException);

            });

        });

        context('when a value of the array returned by the provider ->getExtensions() is not a callable', function () {

            it('should throw an UnexpectedValueException', function () {

                $this->provider->getFactories->returns([]);

                $this->provider->getExtensions->returns();

                expect([$this->configuration, 'entry'])->toThrow(new UnexpectedValueException);

            });

        });

    });

});
