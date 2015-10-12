<?php

namespace Symsite\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symsite\Bundle\AdminBundle\Entity\BaseEntity;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Symsite\Bundle\BlogBundle\Entity\TagRepository")
 * @UniqueEntity("name")
 * @ORM\HasLifecycleCallbacks
 */
class Tag extends BaseEntity
{
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $articles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add article
     *
     * @param \Symsite\Bundle\BlogBundle\Entity\Article $article
     *
     * @return Tag
     */
    public function addArticle(\Symsite\Bundle\BlogBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \Symsite\Bundle\BlogBundle\Entity\Article $article
     */
    public function removeArticle(\Symsite\Bundle\BlogBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
}
