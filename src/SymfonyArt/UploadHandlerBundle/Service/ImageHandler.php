<?php

namespace SymfonyArt\UploadHandlerBundle\Service;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Liip\ImagineBundle\Model\Binary;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Service to handle uploaded image with given filter
 *
 * Class ImageHandler
 * @package AppBundle\Service
 */
class ImageHandler
{
    /**
     * @var CacheManager
     */
    private $imagineCache;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var string
     */
    private $error;

    /**
     * @var \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser
     */
    private $extensionGuesser;

    /**
     * @param CacheManager $imagineCacheManager
     * @param FilterManager $filterManager
     */
    public function __construct(CacheManager $imagineCacheManager, FilterManager $filterManager)
    {
        $this->imagineCache = $imagineCacheManager;
        $this->extensionGuesser = new MimeTypeExtensionGuesser();
        $this->filterManager = $filterManager;
    }

    /**
     * @param string $imageData
     * @param string $filter
     * @throws \Exception
     * @return bool|string
     */
    public function handle($imageData, $filter)
    {
        try {
            $binary = $this->createBinary($imageData);
            $pictureName = time().'.'.$binary->getFormat();

            $this->imagineCache->store(
                $this->filterManager->applyFilter($binary, $filter),
                $pictureName,
                $filter
            );

            $this->imagineCache->resolve($pictureName, $filter);

            return $this->getUploadDir().$filter.'/'.$pictureName;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return false;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Init image from uploaded file
     * @param $imageData
     * @return Binary
     */
    private function createBinary($imageData)
    {
        $f = finfo_open();
        $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);

        $binary = new Binary($imageData, $mimeType, $this->extensionGuesser->guess($mimeType));

        return $binary;
    }

    /**
     * @return string
     */
    private function getUploadDir()
    {
        return '/uploads/';
    }
}