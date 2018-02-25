<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * AuthorTranslation
 */
class AuthorTranslation
{
    /**
     * @var integer
     */
    private $author_id;

    /**
     * @var integer
     */
    private $language_id;

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
     * @var \Rapsys\BlogBundle\Entity\Author
     */
    private $author;


    /**
     * Set authorId
     *
     * @param integer $authorId
     *
     * @return AuthorTranslation
     */
    public function setAuthorId($authorId)
    {
        $this->author_id = $authorId;

        return $this;
    }

    /**
     * Get authorId
     *
     * @return integer
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * Set languageId
     *
     * @param integer $languageId
     *
     * @return AuthorTranslation
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
     * Set description
     *
     * @param string $description
     *
     * @return AuthorTranslation
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
     * @return AuthorTranslation
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
     * @return AuthorTranslation
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
     * Set author
     *
     * @param \Rapsys\BlogBundle\Entity\Author $author
     *
     * @return AuthorTranslation
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
     * @var \Rapsys\BlogBundle\Entity\Language
     */
    private $language;


    /**
     * Set language
     *
     * @param \Rapsys\BlogBundle\Entity\Language $language
     *
     * @return AuthorTranslation
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
