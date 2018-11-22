<?php

namespace AppBundle\Service;

use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class SmartAssetsHelperService.
 *
 * @category Service
 */
class SmartAssetsHelperService
{
    const HTTP_PROTOCOL = 'https://';
    const PHP_SERVER_API_CLI_CONTEXT = 'cli';

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var string mailer URL base
     */
    private $mub;

    /**
     * Methods.
     */

    /**
     * SmartAssetsHelperService constructor.
     *
     * @param KernelInterface $kernel
     * @param string          $mub
     */
    public function __construct(KernelInterface $kernel, $mub)
    {
        $this->kernel = $kernel;
        $this->mub = $mub;
    }

    /**
     * Determine if this PHP script is executed under a CLI context.
     *
     * @return bool
     */
    public function isCliContext()
    {
        return self::PHP_SERVER_API_CLI_CONTEXT === php_sapi_name();
    }

    /**
     * Always return absolute URL path, even in CLI contexts.
     *
     * @param string $assetPath
     *
     * @return string
     */
    public function getAbsoluteAssetPathContextIndependent($assetPath)
    {
        $package = new UrlPackage(self::HTTP_PROTOCOL.$this->mub.'/', new EmptyVersionStrategy());

        return $package->getUrl($assetPath);
    }

    /**
     * If is CLI context returns absolute file path, otherwise returns absolute URL path.
     *
     * @param string $assetPath
     *
     * @return string
     */
    public function getAbsoluteAssetPathByContext($assetPath)
    {
        $result = $this->getAbsoluteAssetPathContextIndependent($assetPath);

        if ($this->isCliContext()) {
            $result = $this->kernel->getRootDir().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.$assetPath;
        }

        return $result;
    }

    /**
     * Always return relative URL path, even in CLI contexts.
     *
     * @param string $assetPath
     *
     * @return string
     */
    public function getRelativeAssetPathContextIndependent($assetPath)
    {
        $package = new UrlPackage('/', new EmptyVersionStrategy());

        return $package->getUrl($assetPath);
    }

    /**
     * If is CLI context returns absolute file path, otherwise returns relative URL path.
     *
     * @param string $assetPath
     *
     * @return string
     */
    public function getRelativeAssetPathByContext($assetPath)
    {
        $result = $this->getRelativeAssetPathContextIndependent($assetPath);

        if ($this->isCliContext()) {
            $result = $this->kernel->getRootDir().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.$assetPath;
        }

        return $result;
    }

    /**
     * Returns absolute file path.
     *
     * @param string $assetPath
     *
     * @return string
     */
    public function getAbsoluteAssetFilePath($assetPath)
    {
        $result = $this->kernel->getRootDir().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.$assetPath;

        return $result;
    }
}
