<?php declare(strict_types=1);

namespace Quanta\Container;

use Interop\Container\ServiceProviderInterface;

final class ImportedConfiguration implements ConfigurationSourceInterface
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
            if ($this->isServiceProviderClassName($value)) {
                $providers[] = new $value;
            }

            if ($this->isServiceProviderInstance($value)) {
                $providers[] = $value;
            }
        }

        return new MergedConfiguration(
            ...array_map([ServiceProviderConfiguration::class, 'instance'], $providers)
        );
    }

    /**
     * Return whether the given value is a service provider class name.
     *
     * @param mixed $value
     * @return bool
     */
    private function isServiceProviderClassName($value): bool
    {
        return is_string($value)
            && class_exists($value)
            && is_subclass_of($value, ServiceProviderInterface::class, true);
    }

    /**
     * Return whether the given value is a service provider implementation.
     *
     * @param mixed $value
     * @return bool
     */
    private function isServiceProviderInstance($value): bool
    {
        return is_object($value)
            && $value instanceof ServiceProviderInterface;
    }
}
