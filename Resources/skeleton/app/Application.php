<?php
@include_once __DIR__ . '/bootstrap.php.cache';
if (!defined('SRCDIR')) {
    require_once __DIR__ . '/autoload.php';
    $command =  'php ' . SNAPDIR . '/vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php ' . __DIR__;
    echo 'Creating bootstrap.php.cache: ' . $command . PHP_EOL;
    exec($command);
    require_once __DIR__ . '/bootstrap.php.cache';
}

require_once __DIR__ . '/AppKernel.php';

use Millwright\RadBundle\Application\RadApplication;

class Application extends RadApplication
{
    const VERSION = '0.0.0';
    const BUILD   = '0';

    protected $selfFile = __FILE__;
}
