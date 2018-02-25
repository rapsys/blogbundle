<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * KeywordTranslation
 */
class KeywordTranslation
{
    /**
     * @var integer
     */
    private $keyword_id;

    /**
     * @var integer
     */
    private $language_id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Rapsys\BlogBundle\Entity\Keyword
     */
    private $keyword;

    /**
     * @var \Rapsys\BlogBundle\Entity\Language
     */
    private $language;


    /**
     * Set keywordId
     *
     * @param integer $keywordId
     *
     * @return KeywordTranslation
     */
    public function setKeywordId($keywordId)
    {
        $this->keyword_id = $keywordId;

        return $this;
    }

    /**
     * Get keywordId
     *
     * @return integer
     */
    public function getKeywordId()
    {
        return $this->keyword_id;
    }

    /**
     * Set languageId
     *
     * @param integer $languageId
     *
     * @return KeywordTranslation
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
     * @return KeywordTranslation
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return KeywordTranslation
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
     * @return KeywordTranslation
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
     * Set keyword
     *
     * @param \Rapsys\BlogBundle\Entity\Keyword $keyword
     *
     * @return KeywordTranslation
     */
    public function setKeyword(\Rapsys\BlogBundle\Entity\Keyword $keyword = null)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return \Rapsys\BlogBundle\Entity\Keyword
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set language
     *
     * @param \Rapsys\BlogBundle\Entity\Language $language
     *
     * @return KeywordTranslation
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
     * @return KeywordTranslation
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
    /**
     * @var string
     */
    private $description;


    /**
     * Set description
     *
     * @param string $description
     *
     * @return KeywordTranslation
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
}
