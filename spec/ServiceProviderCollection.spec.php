<?php

use function Eloquent\Phony\Kahlan\mock;

use Interop\Container\ServiceProviderInterface;

use Quanta\Container\MergedConfiguration;
use Quanta\Container\ServiceProviderCollection;
use Quanta\Container\ConfigurationSourceInterface;
use Quanta\Container\ServiceProviderConfiguration;

describe('ServiceProviderCollection', function () {

    context('when there is no service provider', function () {

        beforeEach(function () {

            $this->source = new ServiceProviderCollection;

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

    context('when there is at least one service provider', function () {

        beforeEach(function () {

            $this->provider1 = mock(ServiceProviderInterface::class);
            $this->provider2 = mock(ServiceProviderInterface::class);
            $this->provider3 = mock(ServiceProviderInterface::class);

            $this->source = new ServiceProviderCollection(...[
                $this->provider1->get(),
                $this->provider2->get(),
                $this->provider3->get(),
            ]);

        });

        it('should implement ConfigurationSourceInterface', function () {

            expect($this->source)->toBeAnInstanceOf(ConfigurationSourceInterface::class);

        });

        describe('->configuration()', function () {

            it('should return a merged configuration', function () {

                $test = $this->source->configuration();

                expect($test)->toEqual(new MergedConfiguration(...[
                    new ServiceProviderConfiguration($this->provider1->get()),
                    new ServiceProviderConfiguration($this->provider2->get()),
                    new ServiceProviderConfiguration($this->provider3->get()),
                ]));

            });

        });

    });

});
