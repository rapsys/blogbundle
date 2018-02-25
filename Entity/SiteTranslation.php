<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * SiteTranslation
 */
class SiteTranslation
{
    /**
     * @var integer
     */
    private $site_id;

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
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Rapsys\BlogBundle\Entity\Site
     */
    private $site;

    /**
     * @var \Rapsys\BlogBundle\Entity\Language
     */
    private $language;


    /**
     * Set siteId
     *
     * @param integer $siteId
     *
     * @return SiteTranslation
     */
    public function setSiteId($siteId)
    {
        $this->site_id = $siteId;

        return $this;
    }

    /**
     * Get siteId
     *
     * @return integer
     */
    public function getSiteId()
    {
        return $this->site_id;
    }

    /**
     * Set languageId
     *
     * @param integer $languageId
     *
     * @return SiteTranslation
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
     * @return SiteTranslation
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
     * @return SiteTranslation
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return SiteTranslation
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
     * @return SiteTranslation
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
     * Set site
     *
     * @param \Rapsys\BlogBundle\Entity\Site $site
     *
     * @return SiteTranslation
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
     * Set language
     *
     * @param \Rapsys\BlogBundle\Entity\Language $language
     *
     * @return SiteTranslation
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
}
