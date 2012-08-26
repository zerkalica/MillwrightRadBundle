<?php
namespace Millwright\RadBundle\Application;

use Symfony\Component\Console\Input\ArgvInput;
use \AppKernel;

class RadApplication
{
    protected $timezone;

    protected $debugMap = array('prod' => false, 'dev' => true, 'test' => true);

    protected $selfFile;

    private $environment;
    private $isCli = false;
    private $input;
    private $root;
    private $availableEnvs;

    static private $instance;

    protected function __construct($environment = null)
    {
        if ($environment) {
            $this->environment = $environment;
        }
        if (null === $this->selfFile) {
            throw new \InvalidArgumentException('put protected $selfFile = __FILE__; in Application class');
        }
        $this->isCli = PHP_SAPI === 'cli';
        $this->root  = dirname($this->selfFile);

        if ($this->isCli) {
            $this->input = new ArgvInput;
        }

        $this->setup();
    }

    static public function getInstance($environment = null)
    {
        if (null === self::$instance) {
            self::$instance = new static($environment);
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

    protected function getEnv()
    {
        $environment = $this->environment;
        if (!$environment) {
            $environment = $this->getEnvFromFile();
        }

        if (getenv('SYMFONY_ENV')) {
            $environment = getenv('SYMFONY_ENV');
        }

        if ($this->isCli) {
            $environment = $this->input->getParameterOption(array('--env', '-e'), $environment);
        }
        $this->checkEnv($environment);

        return $environment;
    }

    protected function getDebug($environment)
    {
        $debug = null;
        if ($this->isCli && $this->input->hasParameterOption(array('--debug', '-d'))) {
            $debug = true;
        }
        if ($debug === null) {
            $debug = ($environment && isset($this->debugMap[$environment])) ? $this->debugMap[$environment] : false;
        }

        return $debug;
    }

    protected function getEnvFilePath()
    {
        return $this->root . '/environment.txt';
    }

    protected function getEnvFromFile()
    {
        $file = $this->getEnvFilePath();

        return file_exists($file) ? file_get_contents($file) : null;
    }

    public function setEnv($env)
    {
        $this->checkEnv($env);
        $file = $this->getEnvFilePath();
        file_put_contents($file, $env);

        return $this;
    }

    public function getAvailableEnvs()
    {
        if (null === $this->availableEnvs) {
            $this->availableEnvs = array_keys($this->debugMap);
        }

        return $this->availableEnvs;
    }

    protected function checkEnv($environment)
    {
        $envs = $this->getAvailableEnvs();
        if (!$environment || !in_array($environment, $envs)) {
            $options = '[' . implode(', ', $envs) . ']';
            $message = 'Set an environment: app/console millwright:rad:setenv --env=' . $options . ' --name=' . $options;
            echo $message . PHP_EOL;
            exit(1);
        }
    }

    public function createKernel()
    {
        $environment = $this->getEnv();
        $debug       = $this->getDebug($environment);

        return new AppKernel($environment, $debug);
    }

    public function getSelfFile()
    {
        return $this->selfFile;
    }
}
