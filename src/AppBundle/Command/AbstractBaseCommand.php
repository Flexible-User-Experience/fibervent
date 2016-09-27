<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

abstract class AbstractBaseCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var bool
     */
    protected $forceOptionIsEnabled = false;

    /**
     * @var UploaderHelper
     */
    protected $uploaderHelper;

    /**
     * @var DataManager
     */
    protected $dataManager;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var FilterManager
     */
    protected $filterManager;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * Load column data from searched array if exists, else throws an exception.
     *
     * @param int   $colIndex
     * @param array $searchArray
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function loadColumnData($colIndex, $searchArray)
    {
        if (!array_key_exists($colIndex, $searchArray)) {
            throw new \Exception($colIndex . ' doesn\'t exists');
        }

        return $searchArray[$colIndex];
    }

    /**
     * Get current timestamp string with format Y/m/d H:i:s.
     *
     * @return string
     */
    protected function getTimestamp()
    {
        $cm = new \DateTime();

        return $cm->format('Y/m/d H:i:s · ');
    }

    /**
     * Set Doctrine Flush and Clear.
     */
    protected function doctrineFlushClear()
    {
        $this->em->flush();
        $this->em->clear();
    }

    /**
     * @param mixed $object
     */
    protected function persistObject($object)
    {
        if ($this->forceOptionIsEnabled) {
                $this->em->persist($object);
                $this->em->flush();
        }
    }
}
