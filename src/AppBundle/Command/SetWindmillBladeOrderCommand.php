<?php

namespace AppBundle\Command;

use AppBundle\Entity\WindmillBlade;
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
                'remove-code',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will set an empty string in code field value'
            )
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
        $this->em   = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $itemsFound = 0;
        $dtStart    = new \DateTime();

        $windmillBlades = $this->em->getRepository('AppBundle:WindmillBlade')->findAll();
        /** @var WindmillBlade $windmillBlade */
        foreach ($windmillBlades as $windmillBlade) {
            $lastChar = substr($windmillBlade->getCode(), -1);
            $windmillBlade->setOrder(intval($lastChar));
            $output->writeln('ID: ' . $windmillBlade->getId() . ' | code: ' . $windmillBlade->getCode() . ' | order: ' . $windmillBlade->getOrder());
            if ($input->getOption('remove-code') == true) {
                $windmillBlade->setCode('');
            }
            $itemsFound = $itemsFound + 1;
        }

        if ($input->getOption('force') == true) {
            $this->em->flush();
        }
        $dtEnd = new \DateTime();

        $output->writeln('<comment>--------------------------------------</comment>');
        $output->writeln('<info>' . $itemsFound . ' items managed in ' . $dtStart->diff($dtEnd)->format('%H:%I:%S') . ' seconds</info>');
        $output->writeln('<comment>END OF FILE.</comment>');
    }
}
