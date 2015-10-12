<?php

namespace Symsite\Bundle\UploadFileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symsite\Bundle\AdminBundle\Entity\BaseEntity;

/**
 * UploadFile
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Symsite\Bundle\UploadFileBundle\Entity\UploadFileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UploadFile extends BaseEntity
{
    const UPLOAD_PATH = 'uploads';

    private $root_path;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=24, nullable=true)
     */
    private $extension;

    private $temp_path;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    private $size;

    /**
     * @var UploadedFile
     *
     * @Assert\NotBlank()
     * @Assert\File(
     *      maxSize="1024k",
     *      mimeTypes={
     *          "application/pdf",
     *          "application/x-pdf",
     *          "image/gif",
     *          "image/jpeg",
     *          "image/png",
     *      }
     * )
     */
    private $file;

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old file path to delete after the update
            $this->temp_path = $this->getAbsolutePath();
        }
        $this->extension = null;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->extension = $this->getFile()->guessExtension();
            $this->size = $this->getFile()->getSize();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move(
          $this->getUploadRootDir(),
          $this->getFilename()
        );

        // check if we have an old image
        if (isset($this->temp_path)) {
            // delete the old image
            unlink($this->temp_path);
            $this->temp_path = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilePathForRemove()
    {
        $this->temp_path = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp_path)) {
            unlink($this->temp_path);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->extension ? null : $this->getUploadRootDir() . '/' . $this->getFilename();
    }

    public function getWebPath()
    {
        return null === $this->extension ? null : '/' . $this->getUploadDir() . '/' . $this->getFilename();
    }

    protected function getUploadRootDir()
    {
        return $this->root_path . '/web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return UploadFile::UPLOAD_PATH;
    }

    public function getFilename()
    {
        return $this->id . '.' . $this->getExtension();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return UploadFile
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return UploadFile
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    public function setRootPath($root_path)
    {
        $this->root_path = $root_path;
    }
}
