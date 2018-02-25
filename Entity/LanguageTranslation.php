<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * LanguageTranslation
 */
class LanguageTranslation
{
    /**
     * @var integer
     */
    private $language_id;

    /**
     * @var integer
     */
    private $target_id;

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
     * @var \Rapsys\BlogBundle\Entity\Language
     */
    private $language;

    /**
     * @var \Rapsys\BlogBundle\Entity\Language
     */
    private $target;


    /**
     * Set languageId
     *
     * @param integer $languageId
     *
     * @return LanguageTranslation
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
     * Set targetId
     *
     * @param integer $targetId
     *
     * @return LanguageTranslation
     */
    public function setTargetId($targetId)
    {
        $this->target_id = $targetId;

        return $this;
    }

    /**
     * Get targetId
     *
     * @return integer
     */
    public function getTargetId()
    {
        return $this->target_id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return LanguageTranslation
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
     * @return LanguageTranslation
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
     * @return LanguageTranslation
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
     * Set language
     *
     * @param \Rapsys\BlogBundle\Entity\Language $language
     *
     * @return LanguageTranslation
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
     * Set target
     *
     * @param \Rapsys\BlogBundle\Entity\Language $target
     *
     * @return LanguageTranslation
     */
    public function setTarget(\Rapsys\BlogBundle\Entity\Language $target = null)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return \Rapsys\BlogBundle\Entity\Language
     */
    public function getTarget()
    {
        return $this->target;
    }
}
