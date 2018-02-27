<?php

namespace Ornito\ObservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gumlet\ImageResize;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Ornito\ObservationBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * The folder to store images from directory web/
     */
    const FOLDER = 'uploads/photos';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=8)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var UploadedFile
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage = "Le fichier est trop volumineux. La taille maximale autorisée est 1024k.",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png"},
     *     mimeTypesMessage = "Le type mime du fichier est invalide. Les types mime autorisés sont jpeg, jpg et png.")
     */
    private $file;

    /**
     * @var string
     */
    private $tempFileName;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     *
     * Set properties before saving its into database
     */
    public function preUpload()
    {
        // Leave when no image need to be uploaded
        if ($this->file === null) {
            return;
        }

        $this->extension = $this->file->guessExtension();
        $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Move uploaded file into the directory when registered into database
     */
    public function upload()
    {
        // Leave when no image need to be uploaded
        if ($this->file === null) {
            return;
        }

        // Delete temporary file when existing
        if ($this->tempFileName !== null) {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFileName;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $image = new ImageResize($this->file);
        $image->resizeToWidth(700);
        if ($this->extension = "png") {
            $image->save($this->getUploadRootDir().'/'.$this->getId().'.'.$this->getExtension(), IMAGETYPE_PNG);
        } else {
            $image->save($this->getUploadRootDir().'/'.$this->getId().'.'.$this->getExtension(), IMAGETYPE_JPEG);
        }

        // Move uploaded file into the directory
        //$this->file->move(
        //    $this->getUploadRootDir(),
        //    $this->id.'.'.$this->extension
        //);
    }

    /**
     * @ORM\PreRemove()
     *
     * Set the file's name into a property before erase from database
     */
    public function preRemoveUpload()
    {
        // Save the file's name in tempFileName before erase it in database
        $this->tempFileName = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
    }

    /**
     * @ORM\PostRemove()
     *
     * Delete the file from the directory after it is deleted from the database
     */
    public function removeUpload()
    {
        // Delete the file into the directory if it exists
        if (file_exists($this->tempFileName)) {
            unlink($this->tempFileName);
        }
    }

    public function getUploadDir()
    {
        // Return the relative path to the image for a browser (relative to the directory /web)
        return self::FOLDER;
    }

    protected function getUploadRootDir()
    {
        // Return the absolute path to the image
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getWebPath()
    {
        // Return
        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getExtension();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set extension.
     *
     * @param string $extension
     *
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set alt.
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt.
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        // When a file is already saved, we set the properties
        if ($this->extension !== null) {
            $this->tempFileName = $this->extension;
            $this->extension = null;
            $this->alt = null;
        }
    }
}
