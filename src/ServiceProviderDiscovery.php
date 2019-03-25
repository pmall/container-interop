<?php declare(strict_types=1);

namespace Quanta\Container\Configuration;

use Interop\Container\ServiceProviderInterface;

final class ServiceProviderDiscovery implements ConfigurationSourceInterface
{
    /**
     * The collection of class names or objects.
     *
     * Those implementing ServiceProviderInterface will be imported.
     *
     * @var iterable
     */
    private $collection;

    /**
     * Constructor.
     *
     * @param iterable $collection
     */
    public function __construct(iterable $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @inheritdoc
     */
    public function configuration(): ConfigurationInterface
    {
        $providers = [];

        foreach ($this->collection as $value) {
            if (is_string($value) && $this->isServiceProviderClassName($value)) {
                $providers[] = new $value;
            }

            if ($value instanceof ServiceProviderInterface) {
                $providers[] = $value;
            }
        }

        return new MergedConfiguration(
            ...array_map([ServiceProviderAdapter::class, 'instance'], $providers)
        );
    }

    /**
     * Return whether the given string is a service provider class name.
     *
     * @param string $value
     * @return bool
     */
    private function isServiceProviderClassName(string $value): bool
    {
        try {
            $reflection = new \ReflectionClass($value);

            return ! $reflection->isInterface()
                && ! $reflection->isAbstract()
                && $reflection->implementsInterface(ServiceProviderInterface::class);
        }

        catch (\ReflectionException $e) {
            return false;
        }
    }
}
