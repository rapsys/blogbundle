<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * Article
 */
class Article
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
    private $article_translations;

    /**
     * @var \Rapsys\BlogBundle\Entity\Site
     */
    private $site;

    /**
     * @var \Rapsys\BlogBundle\Entity\Author
     */
    private $author;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $keywords;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->article_translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->keywords = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Article
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
     * @return Article
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
     * Add articleTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\ArticleTranslation $articleTranslation
     *
     * @return Article
     */
    public function addArticleTranslation(\Rapsys\BlogBundle\Entity\ArticleTranslation $articleTranslation)
    {
        $this->article_translations[] = $articleTranslation;

        return $this;
    }

    /**
     * Remove articleTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\ArticleTranslation $articleTranslation
     */
    public function removeArticleTranslation(\Rapsys\BlogBundle\Entity\ArticleTranslation $articleTranslation)
    {
        $this->article_translations->removeElement($articleTranslation);
    }

    /**
     * Get articleTranslations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticleTranslations()
    {
        return $this->article_translations;
    }

    /**
     * Set site
     *
     * @param \Rapsys\BlogBundle\Entity\Site $site
     *
     * @return Article
     */
    public function setSite(\Rapsys\BlogBundle\Entity\Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return \Rapsys\BlogBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set author
     *
     * @param \Rapsys\BlogBundle\Entity\Author $author
     *
     * @return Article
     */
    public function setAuthor(\Rapsys\BlogBundle\Entity\Author $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Rapsys\BlogBundle\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add keyword
     *
     * @param \Rapsys\BlogBundle\Entity\Keyword $keyword
     *
     * @return Article
     */
    public function addKeyword(\Rapsys\BlogBundle\Entity\Keyword $keyword)
    {
        $this->keywords[] = $keyword;

        return $this;
    }

    /**
     * Remove keyword
     *
     * @param \Rapsys\BlogBundle\Entity\Keyword $keyword
     */
    public function removeKeyword(\Rapsys\BlogBundle\Entity\Keyword $keyword)
    {
        $this->keywords->removeElement($keyword);
    }

    /**
     * Get keywords
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Article
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
