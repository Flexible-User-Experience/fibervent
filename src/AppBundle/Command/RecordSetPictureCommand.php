<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecordSetPictureCommand extends AbstractBaseCommand
{
    protected function configure()
    {
        $this
            ->setName('app:recordset:picture')
            ->setDescription('Picture RecordSet from Photo and BladePhoto entities');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Welcome to RecordSet Picture command</info>');

        // Validate arguments
        $this->em             = $this->getContainer()->get('doctrine');
        $this->filterManager  = $this->getContainer()->get('liip_imagine.filter.manager');
        $this->cacheManager   = $this->getContainer()->get('liip_imagine.cache.manager');
        $this->dataManager    = $this->getContainer()->get('liip_imagine.data.manager');
        $this->uploaderHelper = $this->getContainer()->get('vich_uploader.templating.helper.uploader_helper');

        $output->writeln('RecordSetting pictures, please wait...');

        $photos = $this->em->getRepository('AppBundle:Photo')->findAll();

        $photosFound = 0;
        $photosNotFound = 0;

        foreach($photos as $photo) {
            $path = $this->uploaderHelper->asset($photo, 'imageFile');
            $output->write('Photo #' . $photo->getId() . ' ' . $photo->getImageName() . ' ');
            if ($path) {
                $binary = $this->dataManager->find('960x540', $path);
                if (!$binary) {
//                    $this->cacheManager->store(
//                        $this->filterManager->applyFilter($binary, '960x540'),
//                        $path,
//                        '960x540'
//                    );
                    $output->writeln($path . ' Created');
                    $photosFound = $photosFound + 1;
                } else {
                    $output->writeln($path . ' <error>Not created</error>');
                    $photosNotFound = $photosNotFound + 1;
                }
            }
        }

            $bladePhotos = $this->em->getRepository('AppBundle:BladePhoto')->findAll();

            $blPhotosFound = 0;
            $blPhotosNotFound = 0;

            foreach($bladePhotos as $bladePhoto)
            {
                $path = $this->uploaderHelper->asset($bladePhoto, 'imageFile');
                $output->write('Blade Photo #' . $bladePhoto->getId() .' '. $bladePhoto->getImageName() . ' ');
                if ($path) {
                    $binary = $this->dataManager->find('960x540', $path);
                    if (!$binary) {
//                    $this->cacheManager->store(
//                        $this->filterManager->applyFilter($binary, '960x540'),
//                        $path,
//                        '960x540'
//                    );
                        $output->writeln($path.' Created');
                        $blPhotosFound = $blPhotosFound + 1;
                    } else {
                        $output->writeln($path.' <error>Not created</error>');
                        $blPhotosNotFound = $blPhotosNotFound + 1;
                    }
                }
            }
        $output->writeln('Total records ' . ($photosFound + $photosNotFound + $blPhotosFound + $blPhotosNotFound));
        $output->writeln('Created Photos '. $photosFound);
        $output->writeln('Errors Photos'. $photosNotFound);
        $output->writeln('Created BlPhotos'. $blPhotosFound);
        $output->writeln('Errors BlPhotos'. $blPhotosNotFound);
    }
}
