<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * Site
 */
class Site
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $domain;

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
    private $site_translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->site_translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set domain
     *
     * @param string $domain
     *
     * @return Site
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Site
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
     * @return Site
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
     * @return Site
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
     * Add siteTranslation
     *
     * @param \Rapsys\BlogBundle\Entity\SiteTranslation $siteTranslation
     *
     * @return Site
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
     * Set id
     *
     * @param integer $id
     *
     * @return Site
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
