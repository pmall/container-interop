<?php

use function Eloquent\Phony\Kahlan\mock;

use Interop\Container\ServiceProviderInterface;

use Quanta\Container\MergedConfigurationEntry;
use Quanta\Container\InteropConfigurationEntry;
use Quanta\Container\InteropConfigurationSource;
use Quanta\Container\ConfigurationSourceInterface;

describe('InteropConfigurationSource', function () {

    context('when there is no service provider', function () {

        beforeEach(function () {

            $this->source = new InteropConfigurationSource;

        });

        it('should implement ConfigurationSourceInterface', function () {

            expect($this->source)->toBeAnInstanceOf(ConfigurationSourceInterface::class);

        });

        describe('->entry()', function () {

            it('should return an empty merged configuration entry', function () {

                $test = $this->source->entry();

                expect($test)->toEqual(new MergedConfigurationEntry);

            });

        });

    });

    context('when there is at least one service provider', function () {

        beforeEach(function () {

            $this->provider1 = mock(ServiceProviderInterface::class);
            $this->provider2 = mock(ServiceProviderInterface::class);
            $this->provider3 = mock(ServiceProviderInterface::class);

            $this->source = new InteropConfigurationSource(...[
                $this->provider1->get(),
                $this->provider2->get(),
                $this->provider3->get(),
            ]);

        });

        it('should implement ConfigurationSourceInterface', function () {

            expect($this->source)->toBeAnInstanceOf(ConfigurationSourceInterface::class);

        });

        describe('->entry()', function () {

            it('should create interop configuration entries from the service providers and merge them', function () {

                $test = $this->source->entry();

                expect($test)->toEqual(new MergedConfigurationEntry(...[
                    new InteropConfigurationEntry($this->provider1->get()),
                    new InteropConfigurationEntry($this->provider2->get()),
                    new InteropConfigurationEntry($this->provider3->get()),
                ]));

            });

        });

    });

});
