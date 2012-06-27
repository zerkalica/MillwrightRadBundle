<?php
namespace Millwright\RadBundle;

use Symfony\Component\HttpKernel\DependencyInjection\Extension as ExtensionBase;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Base rad extension
 */
abstract class Extension extends ExtensionBase
{
    protected $configRoot = null;

    protected function getConfigParts()
    {
        return array(
            'services.yml',
        );
    }

    protected function getLoader(ContainerBuilder $container)
    {
        return new Loader\YamlFileLoader($container, new FileLocator(dirname($this->configRoot) . '/Resources/config'));
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = $this->getLoader($container);

        foreach ($this->getConfigParts() as $part) {
            $loader->load($part);
        }

        $configuration = $this->getConfiguration(array(), $container);
        if ($configuration) {
            $config = $this->processConfiguration($configuration, $configs);
            $this->copyParameters($config, $container);
        }
    }

    /**
     * Copy parameters from config to container
     *
     * @param array            $config app config
     * @param ContainerBuilder $container
     */
    protected function copyParameters(array $config, ContainerBuilder $container)
    {
        foreach ($config as $key => $value) {
            $key = $this->getAlias() . '.' . $key;
            $container->setParameter($key, $value);
        }
    }
}
