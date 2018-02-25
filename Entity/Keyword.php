<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * Keyword
 */
class Keyword
{
    /**
     * @var integer
     */
    private $id;

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
    private $keyword_translations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $articles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->keyword_translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Keyword
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
     * @return Keyword
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
     * Add keywordTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\KeywordTranslation $keywordTranslation
     *
     * @return Keyword
     */
    public function addKeywordTranslation(\Rapsys\BlogBundle\Entity\KeywordTranslation $keywordTranslation)
    {
        $this->keyword_translations[] = $keywordTranslation;

        return $this;
    }

    /**
     * Remove keywordTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\KeywordTranslation $keywordTranslation
     */
    public function removeKeywordTranslation(\Rapsys\BlogBundle\Entity\KeywordTranslation $keywordTranslation)
    {
        $this->keyword_translations->removeElement($keywordTranslation);
    }

    /**
     * Get keywordTranslations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKeywordTranslations()
    {
        return $this->keyword_translations;
    }

    /**
     * Add article
     *
     * @param \Rapsys\BlogBundle\Entity\Article $article
     *
     * @return Keyword
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
     * Set id
     *
     * @param integer $id
     *
     * @return Keyword
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
