<?php
namespace Millwright\RadBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console command for environment switching
 */
class SwitchEnvCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('millwright:rad:setenv')
            ->setDescription('Set current environment')
            ->setDefinition(array(new InputOption('env', null, InputOption::VALUE_REQUIRED, 'Environment id: prod, test, dev'),));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = \Application::getInstance()->getEnvFileName();
        $env = $input->getOption('env');

        file_put_contents($filename, $env);

        $output->writeln(sprintf('Environment set to <comment>%s</comment>', $env));
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception Throws if some options empty
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('env')) {
            $env = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose an environment:',
                function($env) {
                    if (empty($env) || !in_array($env, \Application::getInstance()->getAvailableEnvs())) {
                        throw new \Exception('Environment must be on of the ' . implode(', ', \Application::getInstance()->getAvailableEnvs()));
                    }

                    return $env;
                }
            );

            $input->setOption('env', $env);
        }
    }
}
