<?php declare(strict_types=1);

namespace Quanta\Container\Configuration;

use Interop\Container\ServiceProviderInterface;

final class ServiceProviderCollection implements ConfigurationSourceInterface
{
    /**
     * The array of service providers.
     *
     * @var \Interop\Container\ServiceProviderInterface[]
     */
    private $providers;

    /**
     * Constructor.
     *
     * @param \Interop\Container\ServiceProviderInterface ...$providers
     */
    public function __construct(ServiceProviderInterface ...$providers)
    {
        $this->providers = $providers;
    }

    /**
     * @inheritdoc
     */
    public function configuration(): ConfigurationInterface
    {
        return new MergedConfiguration(
            ...array_map([ServiceProviderAdapter::class, 'instance'], $this->providers)
        );
    }
}
