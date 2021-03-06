<?php
namespace Millwright\RadBundle\DependencyInjection;

use Millwright\Util\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MillwrightRadExtension extends Extension
{
    protected $bundleRoot = __DIR__;

    /**
     * {@inheritDoc}
     */
    protected function getConfigParts()
    {
        return array(
            'form.yml',
            'twig.yml',
            'validators.yml',
        );
    }
}
