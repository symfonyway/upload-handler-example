<?php

namespace SymfonyArt\UploadHandlerBundle\Entity;

/**
 * Interface ImageUploadableInterface
 * @package AppBundle\Entity
 */
interface ImageUploadableInterface
{
    /**
     * Should be an array with path property name as a key and filter name as a value.
     * Entity must have two properties: one of string type (to store path), another one for UploadedFile instance
     * Example: $image and $imageFile
     *
     * @return array
     */
    public function getImageProperties();
} 