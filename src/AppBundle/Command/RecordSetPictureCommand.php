<?php

namespace AppBundle\Command;

use AppBundle\Entity\BladePhoto;
use AppBundle\Entity\Photo;
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
                $binary = $this->dataManager->find('600x960', $path);
                if (!$binary) {
//                    $this->cacheManager->store(
//                        $this->filterManager->applyFilter($binary, '600x960'),
//                        $path,
//                        '600x960'
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
            if ($path) {
                $binary = $this->dataManager->find('960x540', $path);
                if ($binary) {
//                    $this->cacheManager->store(
//                        $this->filterManager->applyFilter($binary, '960x540'),
//                        $path,
//                        '960x540'
//                    );
                    $this->writeMessage($output, $bladePhoto, 'Blade Photo', '960x540', $path, true);
                    $blPhotosFound = $blPhotosFound + 1;
                } else {
                    $this->writeMessage($output, $bladePhoto, 'Blade Photo', '960x540', $path, false);
                    $blPhotosNotFound = $blPhotosNotFound + 1;
                }
                $binary = $this->dataManager->find('600x960', $path);
                if ($binary) {
//                    $this->cacheManager->store(
//                        $this->filterManager->applyFilter($binary, '600x960'),
//                        $path,
//                        '600x960'
//                    );
                    $this->writeMessage($output, $bladePhoto, 'Blade Photo', '600x960', $path, true);
                    $blPhotosFound = $blPhotosFound + 1;
                } else {
                    $this->writeMessage($output, $bladePhoto, 'Blade Photo', '600x960', $path, false);
                    $blPhotosNotFound = $blPhotosNotFound + 1;
                }
            }
        }
        $output->writeln('Total records ' . ($photosFound + $photosNotFound + $blPhotosFound + $blPhotosNotFound));
        $output->writeln('Created Photos '. $photosFound);
        $output->writeln('Errors Photos '. $photosNotFound);
        $output->writeln('Created BlPhotos '. $blPhotosFound);
        $output->writeln('Errors BlPhotos '. $blPhotosNotFound);
    }

    /**
     * @param OutputInterface  $output
     * @param Photo|BladePhoto $object
     * @param string           $type
     * @param string           $filter
     * @param string           $path
     * @param boolean          $isCreated
     */
    private function writeMessage(OutputInterface $output, $object, $type, $filter, $path, $isCreated)
    {
        if ($isCreated) {
            $ending = 'Created';
        } else {
            $ending = '<error>Not created</error>';
        }
        $output->writeln($type.' #'.$object->getId().' '. $filter.' '.$object->getImageName().' '.$path.' '.$ending);
    }
}
