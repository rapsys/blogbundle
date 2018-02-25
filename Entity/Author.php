<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * Author
 */
class Author
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $articles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $author_translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->author_translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Author
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Author
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Author
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add article
     *
     * @param \Rapsys\BlogBundle\Entity\Article $article
     *
     * @return Author
     */
    public function addArticle(\Rapsys\BlogBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \Rapsys\BlogBundle\Entity\Article $article
     */
    public function removeArticle(\Rapsys\BlogBundle\Entity\Article $article)
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

    /**
     * Add authorTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\AuthorTranslation $authorTranslation
     *
     * @return Author
     */
    public function addAuthorTranslation(\Rapsys\BlogBundle\Entity\AuthorTranslation $authorTranslation)
    {
        $this->author_translations[] = $authorTranslation;

        return $this;
    }

    /**
     * Remove authorTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\AuthorTranslation $authorTranslation
     */
    public function removeAuthorTranslation(\Rapsys\BlogBundle\Entity\AuthorTranslation $authorTranslation)
    {
        $this->author_translations->removeElement($authorTranslation);
    }

    /**
     * Get authorTranslations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthorTranslations()
    {
        return $this->author_translations;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Author
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @var string
     */
    private $slug;


    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Author
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
