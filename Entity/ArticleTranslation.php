<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * ArticleTranslation
 */
class ArticleTranslation
{
    /**
     * @var integer
     */
    private $article_id;

    /**
     * @var integer
     */
    private $language_id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Rapsys\BlogBundle\Entity\Article
     */
    private $article;

    /**
     * @var \Rapsys\BlogBundle\Entity\Language
     */
    private $language;


    /**
     * Set articleId
     *
     * @param integer $articleId
     *
     * @return ArticleTranslation
     */
    public function setArticleId($articleId)
    {
        $this->article_id = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return integer
     */
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * Set languageId
     *
     * @param integer $languageId
     *
     * @return ArticleTranslation
     */
    public function setLanguageId($languageId)
    {
        $this->language_id = $languageId;

        return $this;
    }

    /**
     * Get languageId
     *
     * @return integer
     */
    public function getLanguageId()
    {
        return $this->language_id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return ArticleTranslation
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
     * Set description
     *
     * @param string $description
     *
     * @return ArticleTranslation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return ArticleTranslation
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return ArticleTranslation
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
     * @return ArticleTranslation
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
     * Set article
     *
     * @param \Rapsys\BlogBundle\Entity\Article $article
     *
     * @return ArticleTranslation
     */
    public function setArticle(\Rapsys\BlogBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Rapsys\BlogBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set language
     *
     * @param \Rapsys\BlogBundle\Entity\Language $language
     *
     * @return ArticleTranslation
     */
    public function setLanguage(\Rapsys\BlogBundle\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Rapsys\BlogBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
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
     * @return ArticleTranslation
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
