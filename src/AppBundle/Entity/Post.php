<?php
/**
 * Author: Dmitry
 * Date: 01.10.15
 * Time: 19:35
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use SymfonyArt\UploadHandlerBundle\Entity\ImageUploadableInterface;

/**
 * Class Post
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="post")
 * @ORM\EntityListeners({ "SymfonyArt\UploadHandlerBundle\EventListener\UploadListener" })
 */
class Post implements ImageUploadableInterface
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * @var UploadedFile
     */
    private $imageFile;

    /**
     * Should be an array with path property name as a key and filter name as a value.
     * Entity must have two properties: one of string type (to store path), another one for UploadedFile instance
     * Example: $image and $imageFile
     *
     * @return array
     */
    public function getImageProperties()
    {
        return array(
            'image' => 'post_thumbnail'
        );
    }

    /**
     * @param int $id
     * @return Post
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $image
     * @return Post
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $name
     * @return Post
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     * @return Post
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
}