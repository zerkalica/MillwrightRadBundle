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
class ShowVersionCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('millwright:rad:version:get')
            ->setDescription('Get current version number')
            ->setDefinition(array(
                new InputOption('parts', null, InputOption::VALUE_OPTIONAL, 'Count of version parts'),
            ));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $partCount = (int) $input->getOption('parts');
        if (!$partCount) {
            $partCount = 2;
        }

        $manager =  $this->getContainer()->get('millwright_rad.version.manager');

        $versionParts = $manager->explodeVersion(\Application::VERSION);

        $result = array();
        foreach ($versionParts as $part) {
            $partCount--;
            $result[] = $part;
            if($partCount <= 0)  {
                break;
            }
        }

        $output->writeln(sprintf('%s', implode('.', $result)));
    }
}
