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
            ->setDefinition(array(new InputOption('environment', null, InputOption::VALUE_REQUIRED, 'Environment id: prod, test, dev'),));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $this->getContainer()->getParameter('kernel.root_dir') . '/environment.txt';
        $env = $input->getOption('environment');

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
        if (!$input->getOption('environment')) {
            $env = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose an environment:',
                function($env) {
                    if (empty($env) || !in_array($env, array('prod', 'dev', 'test'))) {
                        throw new \Exception('Environment mast be: prod, dev, test');
                    }

                    return $env;
                }
            );

            $input->setOption('environment', $env);
        }
    }
}
