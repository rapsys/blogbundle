<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * Language
 */
class Language
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $iso6391;

    /**
     * @var string
     */
    private $iso6393;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $keyword_translations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $site_translations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $language_translations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $target_translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->article_translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->keyword_translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->site_translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->language_translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->target_translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set iso6391
     *
     * @param string $iso6391
     *
     * @return Language
     */
    public function setIso6391($iso6391)
    {
        $this->iso6391 = $iso6391;

        return $this;
    }

    /**
     * Get iso6391
     *
     * @return string
     */
    public function getIso6391()
    {
        return $this->iso6391;
    }

    /**
     * Set iso6393
     *
     * @param string $iso6393
     *
     * @return Language
     */
    public function setIso6393($iso6393)
    {
        $this->iso6393 = $iso6393;

        return $this;
    }

    /**
     * Get iso6393
     *
     * @return string
     */
    public function getIso6393()
    {
        return $this->iso6393;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Language
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
     * @return Language
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
     * @return Language
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
     * Add keywordTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\KeywordTranslation $keywordTranslation
     *
     * @return Language
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
     * Add siteTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\SiteTranslation $siteTranslation
     *
     * @return Language
     */
    public function addSiteTranslation(\Rapsys\BlogBundle\Entity\SiteTranslation $siteTranslation)
    {
        $this->site_translations[] = $siteTranslation;

        return $this;
    }

    /**
     * Remove siteTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\SiteTranslation $siteTranslation
     */
    public function removeSiteTranslation(\Rapsys\BlogBundle\Entity\SiteTranslation $siteTranslation)
    {
        $this->site_translations->removeElement($siteTranslation);
    }

    /**
     * Get siteTranslations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSiteTranslations()
    {
        return $this->site_translations;
    }

    /**
     * Add languageTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\LanguageTranslation $languageTranslation
     *
     * @return Language
     */
    public function addLanguageTranslation(\Rapsys\BlogBundle\Entity\LanguageTranslation $languageTranslation)
    {
        $this->language_translations[] = $languageTranslation;

        return $this;
    }

    /**
     * Remove languageTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\LanguageTranslation $languageTranslation
     */
    public function removeLanguageTranslation(\Rapsys\BlogBundle\Entity\LanguageTranslation $languageTranslation)
    {
        $this->language_translations->removeElement($languageTranslation);
    }

    /**
     * Get languageTranslations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguageTranslations()
    {
        return $this->language_translations;
    }

    /**
     * Add targetTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\LanguageTranslation $targetTranslation
     *
     * @return Language
     */
    public function addTargetTranslation(\Rapsys\BlogBundle\Entity\LanguageTranslation $targetTranslation)
    {
        $this->target_translations[] = $targetTranslation;

        return $this;
    }

    /**
     * Remove targetTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\LanguageTranslation $targetTranslation
     */
    public function removeTargetTranslation(\Rapsys\BlogBundle\Entity\LanguageTranslation $targetTranslation)
    {
        $this->target_translations->removeElement($targetTranslation);
    }

    /**
     * Get targetTranslations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTargetTranslations()
    {
        return $this->target_translations;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Language
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
