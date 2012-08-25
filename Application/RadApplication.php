<?php
namespace Millwright\RadBundle\Application;

use Symfony\Component\Console\Input\ArgvInput;
use \AppKernel;

class RadApplication
{
    protected $timezone;
    protected $envFile  = 'environment.txt';

    protected $availableEnvs = array('prod', 'dev', 'test');

    protected $isCli = false;
    protected $root;
    protected $selfFile;

    static protected $instance;

    protected function __construct($timezone = null, $root = __DIR__)
    {
        if($timezone) {
            $this->timezone = $timezone;
        }
        $this->isCli = PHP_SAPI === 'cli';
        $this->root  = $root;

        $this->setup();
    }

    static public function getInstance($timezone = null, $root = __DIR__)
    {
        if (null === self::$instance) {
            self::$instance = new static($timezone, $root);
        }

        return self::$instance;
    }

    protected function setup()
    {
        // if you don't want to setup permissions the proper way, just uncomment the following PHP line
        // read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
        umask(0000);

        if ($this->isCli) {
            set_time_limit(0);
        }
        if($this->timezone) {
            ini_set('date.timezone', $this->timezone);
        }
    }

    public function getEnvFileName()
    {
        return $this->root . '/' . $this->envFile;
    }

    protected function getEnv()
    {
        $environment = null;
        $debug       = null;
        $envFile     = $this->getEnvFileName();

        if (getenv('SYMFONY_ENV')) {
            $environment = getenv('SYMFONY_ENV');
        } elseif (file_exists($envFile)) {
            $environment = trim(file_get_contents($envFile));
        }

        if (null === $debug) {
            $debug = $environment !== 'prod';
        }

        if ($this->isCli) {
            $input       = new ArgvInput;
            $environment = $input->getParameterOption(array('--env', '-e'), $environment);
            if ($environment != 'prod') {
                $debug = $input->hasParameterOption(array('--no-debug', $debug));
            }
        }

        return array($environment, $debug);
    }

    public function getAvailableEnvs()
    {
        return $this->availableEnvs;
    }

    protected function checkEnv($environment)
    {
        $envs = self::getAvailableEnvs();
        if (!$environment || !in_array($environment, $envs)) {
            $options = '[' . implode(', ', $envs) . ']';
            $message = 'Set an environment: app/console millwright:rad:setenv --env=' . $options . ' --name=' . $options;
            echo $message . PHP_EOL;
            exit(1);
        }
    }

    public function createKernel()
    {
        list($env, $debug) = $this->getEnv();
        $this->checkEnv($env);

        return new AppKernel($env, $debug);
    }

    public function getSelfFile()
    {
        return $this->selfFile;
    }
}
