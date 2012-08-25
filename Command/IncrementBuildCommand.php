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
class IncrementBuildCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('millwright:rad:build:increment')
            ->setDescription('Increment current build number');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $replacer =  $this->getContainer()->get('millwright_rad.version.replacer');
        $build = $replacer->incrementBuild();

        $output->writeln(sprintf('Set build number to <comment>%s</comment>', $build));
    }
}
