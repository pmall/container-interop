<?php declare(strict_types=1);

namespace Quanta\Container;

use Interop\Container\ServiceProviderInterface;

use Quanta\Container\Maps\FactoryMap;
use Quanta\Container\Passes\ExtensionPass;
use Quanta\Container\Passes\MergedProcessingPass;

final class InteropConfigurationEntry implements ConfigurationEntryInterface
{
    /**
     * The service provider to adapt as a configuration.
     *
     * @var \Interop\Container\ServiceProviderInterface
     */
    private $provider;

    /**
     * Constructor.
     *
     * @param \Interop\Container\ServiceProviderInterface $provider
     */
    public function __construct(ServiceProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @inheritdoc
     */
    public function configuration(): Configuration
    {
        $factories = $this->provider->getFactories();

        if (! is_array($factories)) {
            throw new \UnexpectedValueException(
                vsprintf('Return value of %s::getFactories() must be an array, %s returned', [
                    get_class($this->provider),
                    gettype($factories),
                ])
            );
        }

        try {
            $map = new FactoryMap($factories);
        }

        catch (\InvalidArgumentException $e) {
            $invalid = array_diff_key($factories, array_filter($factories, 'is_callable'));

            throw new \UnexpectedValueException(
                vsprintf('Return value of %s::getFactories() must be an array of callables, %s returned for key [%s]', [
                    get_class($this->provider),
                    gettype(current($invalid)),
                    key($invalid),
                ])
            );
        }


        $extensions = $this->provider->getExtensions();

        if (! is_array($extensions)) {
            throw new \UnexpectedValueException(
                vsprintf('Return value of %s::getExtensions() must be an array, %s returned', [
                    get_class($this->provider),
                    gettype($extensions),
                ])
            );
        }

        $passes = [];

        foreach ($extensions as $id => $extension) {
            try {
                $passes[] = new ExtensionPass((string) $id, $extension);
            }

            catch (\TypeError $e) {
                throw new \UnexpectedValueException(
                    vsprintf('Return value of %s::getExtensions() must be an array of callables, %s returned for key [%s]', [
                        get_class($this->provider),
                        gettype($extension),
                        $id,
                    ])
                );
            }
        }

        return new Configuration($map, new MergedProcessingPass(...$passes));
    }
}