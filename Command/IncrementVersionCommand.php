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
class IncrementVersionCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('millwright:rad:version:increment')
            ->setDescription('Increment current version number')
            ->setDefinition(array(
                new InputOption('part', null, InputOption::VALUE_OPTIONAL, 'Version part number to increment: major, minor, extra. Default - extra.'),
            ));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $part     = $input->getOption('part');
        $replacer =  $this->getContainer()->get('millwright_rad.version.replacer');
        $map      = array('major' => 0, 'minor' => 1, 'extra' => 2);
        $part     = isset($map[$part]) ? $map[$part] : null;

        $version = $replacer->incrementVersion($part);
        $output->writeln(sprintf('Set version to <comment>%s</comment>', $version));
    }
}
