<?php
require_once __DIR__ . '/bootstrap.php.cache';
require_once __DIR__ . '/AppKernel.php';

use Millwright\RadBundle\Application\RadApplication;

class Application extends RadApplication
{
    const VERSION = '0.0.0';
    const BUILD   = '0';

    protected $selfFile = __FILE__;
}
