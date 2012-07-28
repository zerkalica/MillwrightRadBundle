<?php
namespace Millwright\RadBundle\DependencyInjection;

use Millwright\ConfigurationBundle\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MillwrightRadExtension extends Extension
{
    protected $configRoot = __DIR__;

    /**
     * {@inheritDoc}
     */
    protected function getConfigParts()
    {
        return array(
            'acl.yml',
            'form.yml',
            'twig.yml',
        );
    }
}
