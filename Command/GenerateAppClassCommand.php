<?php
namespace Millwright\RadBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Millwright\RadBundle\Generator\AppClassGenerator;

/**
 * Console command
 */
class GenerateAppClassCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('millwright:rad:install')
            ->setDescription('Generate Application.php, console, environment.txt, app.php');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $root = $this->getContainer()->getParameter('kernel.root_dir');
        $fs   = $this->getContainer()->get('filesystem');
        $skel = __DIR__ . '/../Resources/skeleton/app';

        $generator = new AppClassGenerator($fs, $skel , $root);
        $generator->generate('Application.php');
        $generator->generate('console');
        $generator->generate('environment.txt');

        $generator = new AppClassGenerator($fs, $skel, $root . '/../web');
        $generator->generate('app.php');

        $output->writeln(sprintf('Classes generated in <comment>%s</comment>', $root));
    }
}
