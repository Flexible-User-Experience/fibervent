<?php

namespace AppBundle\Command;

use AppBundle\Entity\WindmillBlade;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetWindmillBladeOrderCommand extends AbstractBaseCommand
{
    protected function configure()
    {
        $this
            ->setName('app:windmill:blade:order')
            ->setDescription('Set a windmill blade sort order')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will persist changes in database'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Welcome to set windmill blade sort order command</info>');

        if ($input->getOption('force') == true) {
            $output->writeln(
                '<comment>--force option enabled (this option persists changes in database)</comment>'
            );
        }

        // Command Vars
        $this->em        = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $itemsFound      = 0;

        $this->forceOptionIsEnabled = $input->getOption('force');

        $windmillBlades = $this->em->getRepository('AppBundle:WindmillBlade')->findAll();
        /** @var WindmillBlade $windmillBlade */
        foreach ($windmillBlades as $windmillBlade) {
            $lastChar = substr($windmillBlade->getCode(), -1);
            $windmillBlade->setOrder(intval($lastChar));
            $output->writeln('ID: ' . $windmillBlade->getId() . ' | code: ' . $windmillBlade->getCode() . ' | order: ' . $windmillBlade->getOrder());
            $itemsFound = $itemsFound + 1;
        }

        if ($this->forceOptionIsEnabled == true) {
            $this->em->flush();
        }

        $output->writeln('Total items found = ' . $itemsFound);
    }
}
