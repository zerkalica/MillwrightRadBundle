<?php
namespace Millwright\RadBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console command
 */
class SetBuildCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('millwright:rad:build:set')
            ->setDescription('Set build number')
            ->setDefinition(array(new InputOption('number', null, InputOption::VALUE_REQUIRED, 'Build number'),));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $build    = (integer) $input->getOption('number');
        $replacer =  $this->getContainer()->get('millwright_rad.version.replacer');
        $replacer->replaceBuild($build);

        $output->writeln(sprintf('Set build number to <comment>%s</comment>', $build));
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception Throws if some options empty
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('number')) {
            $number = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Enter build number:',
                function($build) {
                    $build = trim($build);
                    if ((string) intval($build) !== $build) {
                        throw new \ErrorException('Must be an integer number');
                    }

                    return $build;
                }
            );

            $input->setOption('number', $number);
        }
    }
}
