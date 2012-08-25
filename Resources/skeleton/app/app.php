<?php
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../app/Application.php';

//require_once __DIR__.'/../app/AppCache.php';
$kernel = Application::getInstance()->createKernel();

$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$kernel->handle(Request::createFromGlobals())->send();
