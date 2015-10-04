<?php

namespace SymfonyArt\UploadHandlerBundle\EventListener;

use Symfony\Component\Security\Acl\Exception\Exception;
use SymfonyArt\UploadHandlerBundle\Entity\ImageUploadableInterface;
use SymfonyArt\UploadHandlerBundle\Exception\UploadHandleException;
use SymfonyArt\UploadHandlerBundle\Service\ImageHandler;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadListener
 * @package AppBundle\EventListener
 *
 * Extend your entity with ImageUploadableInterface and add EntityListener annotation with that class as a value
 */
class UploadListener
{
    /**
     * @var ImageHandler
     */
    private $imageHandler;

    /**
     * @param \SymfonyArt\UploadHandlerBundle\Service\ImageHandler $imageHandler
     */
    public function __construct(ImageHandler $imageHandler)
    {
        $this->imageHandler = $imageHandler;
    }

    /**
     * @param \SymfonyArt\UploadHandlerBundle\Entity\ImageUploadableInterface $entity
     */
    public function preUpdate(ImageUploadableInterface $entity)
    {
        $this->upload($entity);
    }

    /**
     * @param \SymfonyArt\UploadHandlerBundle\Entity\ImageUploadableInterface $entity
     */
    public function prePersist(ImageUploadableInterface $entity)
    {
        $this->upload($entity);
    }

    /**
     * @param \SymfonyArt\UploadHandlerBundle\Entity\ImageUploadableInterface $entity
     * @throws UploadHandleException
     */
    public function upload(ImageUploadableInterface $entity)
    {
        foreach ($entity->getImageProperties() as $property => $filter) {
            if (null === $entity->{'get'.ucfirst($property).'File'}()) {
                continue;
            }

            /** @var UploadedFile $file */
            $file = $entity->{'get'.ucfirst($property).'File'}();
            $path = $this->imageHandler->handle(file_get_contents($file->getRealPath()), $filter);

            if (!$path) {
                throw new UploadHandleException($this->imageHandler->getError());
            }

            $entity->{'set'.ucfirst($property)}($path);
            $entity->{'set'.ucfirst($property).'File'}(null);
        }
    }

    /**
     * @param \SymfonyArt\UploadHandlerBundle\Entity\ImageUploadableInterface $entity
     */
    public function preRemove(ImageUploadableInterface $entity)
    {
        foreach ($entity->getImageProperties() as $property => $filter) {
            $filepath = $entity->{'get'.ucfirst($property)}();

            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }
}