<?php
require_once __DIR__ . '/bootstrap.php.cache';
require_once __DIR__ . '/AppKernel.php';

use Millwright\RadBundle\Application\RadApplication;

class Application extends RadApplication
{
    const VERSION = '1.0.0';
    const BUILD   = '0';

    protected $timezone = null;

    protected $selfFile = __FILE__;

    static public function getInstance($timezone = null, $root = __DIR__)
    {
        return parent::getInstance($timezone, $root);
    }
}
