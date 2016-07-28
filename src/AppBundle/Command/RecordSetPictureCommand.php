<?php

namespace AppBundle\Command;

use AppBundle\Entity\BladePhoto;
use AppBundle\Entity\Photo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecordSetPictureCommand extends AbstractBaseCommand
{
    /**
     * @var int
     */
    private $photosFound = 0;

    /**
     * @var int
     */
    private $photosNotFound = 0;

    /**
     * @var int
     */
    private $blPhotosFound = 0;

    /**
     * @var int
     */
    private $blPhotosNotFound = 0;

    /**
     *
     *
     * Methods
     *
     *
     */

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
        $this->buildImagesCacheCollection($photos, $output, 'Photo');

        $bladePhotos = $this->em->getRepository('AppBundle:BladePhoto')->findAll();
        $this->buildImagesCacheCollection($bladePhotos, $output, 'Blade Photo');

        $output->writeln('Total records ' . ($this->photosFound + $this->photosNotFound + $this->blPhotosFound + $this->blPhotosNotFound));
        $output->writeln('Created Photos '. $this->photosFound);
        $output->writeln('Errors Photos '. $this->photosNotFound);
        $output->writeln('Created BlPhotos '. $this->blPhotosFound);
        $output->writeln('Errors BlPhotos '. $this->blPhotosNotFound);
    }

    /**
     * @param array           $collection
     * @param OutputInterface $output
     * @param string          $type
     */
    private function buildImagesCacheCollection($collection, $output, $type)
    {
        foreach($collection as $item)
        {
            $path = $this->uploaderHelper->asset($item, 'imageFile');
            if ($path) {
                $this->buildImageCache($output, $item, $type, '960x540', $path);
                $this->buildImageCache($output, $item, $type, '600x960', $path);
            }
        }
    }

    /**
     * @param OutputInterface  $output
     * @param Photo|BladePhoto $object
     * @param string           $type
     * @param string           $filter
     * @param string           $path
     */
    private function buildImageCache(OutputInterface $output, $object, $type, $filter, $path)
    {
        $binary = $this->dataManager->find($filter, $path);
        if ($binary) {
            $this->cacheManager->store(
                $this->filterManager->applyFilter($binary, $filter),
                $path,
                $filter
            );
            $this->writeMessage($output, $object, $type, $filter, $path, true);
            if ($object instanceof Photo) {
                $this->photosFound = $this->photosFound + 1;
            } else {
                $this->blPhotosFound = $this->blPhotosFound + 1;
            }
        } else {
            $this->writeMessage($output, $object, $type, $filter, $path, false);
            if ($object instanceof Photo) {
                $this->photosNotFound = $this->photosNotFound + 1;
            } else {
                $this->blPhotosNotFound = $this->blPhotosNotFound + 1;
            }
        }
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
