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
class SetVersionCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('millwright:rad:version:set')
            ->setDescription('Set or increment current version number')
            ->setDefinition(array(
                new InputOption('number', null, InputOption::VALUE_REQUIRED, 'Version number in format $major.$minor.$extra.'),
            ));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $version  = $input->getOption('number');
        $replacer =  $this->getContainer()->get('millwright_rad.version.replacer');

        $replacer->replaceVersion($version);

        $output->writeln(sprintf('Set version to <comment>%s</comment>', $version));
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception Throws if some options empty
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $manager =  $this->getContainer()->get('millwright_rad.version.manager');
        if (!$input->getOption('number')) {
            $number = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Enter version in format $major.$minor.$extra:',
                function($version) use ($manager) {
                    $manager->explodeVersion($version);

                    return $version;
                }
            );

            $input->setOption('number', $number);
        }
    }
}
