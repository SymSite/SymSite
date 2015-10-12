<?php

namespace Symsite\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symsite\Bundle\AdminBundle\Entity\BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Symsite\Bundle\BlogBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Article extends BaseEntity
{
    public static $statuses = ['Published', 'Draft', 'Pending', 'Trash'];

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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     * @Assert\NotBlank()
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=64)
     * @Assert\Choice(callback = {Article::class, "getStatuses"})
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_at", type="datetime")
     * @Assert\DateTime()
     */
    private $publishedAt;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $category;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $tags;

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
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get summary
     *
     * @return mixed
     */
    public function getSummary()
    {
        return explode('<!--more-->', $this->body)[0];
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Article
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Article
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Get statuses
     *
     * @return array
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }

    /**
     * Set category
     *
     * @param \Symsite\Bundle\BlogBundle\Entity\Category $category
     *
     * @return Article
     */
    public function setCategory(\Symsite\Bundle\BlogBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Symsite\Bundle\BlogBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add tag
     *
     * @param \Symsite\Bundle\BlogBundle\Entity\Tag $tag
     *
     * @return Article
     */
    public function addTag(\Symsite\Bundle\BlogBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Symsite\Bundle\BlogBundle\Entity\Tag $tag
     */
    public function removeTag(\Symsite\Bundle\BlogBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
