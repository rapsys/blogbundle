<?php declare(strict_types=1);

/*
 * This file is part of the Rapsys BlogBundle package.
 *
 * (c) Raphaël Gertz <symfony@rapsys.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rapsys\BlogBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Keyword
 */
class Keyword {
	/**
	 * @var int
	 */
	private ?int $id;

	/**
	 * @var \DateTime
	 */
	private \DateTime $created;

	/**
	 * @var \DateTime
	 */
	private \DateTime $updated;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private Collection $keyword_translations;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private Collection $articles;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->created = new \DateTime('now');
		$this->updated = new \DateTime('now');
		$this->keyword_translations = new ArrayCollection();
		$this->articles = new ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return ?int
	 */
	public function getId(): ?int {
		return $this->id;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 *
	 * @return Keyword
	 */
	public function setCreated(\DateTime $created): Keyword {
		$this->created = $created;

		return $this;
	}

	/**
	 * Get created
	 *
	 * @return \DateTime
	 */
	public function getCreated(): \DateTime {
		return $this->created;
	}

	/**
	 * Set updated
	 *
	 * @param \DateTime $updated
	 *
	 * @return Keyword
	 */
	public function setUpdated(\DateTime $updated): Keyword {
		$this->updated = $updated;

		return $this;
	}

	/**
	 * Get updated
	 *
	 * @return \DateTime
	 */
	public function getUpdated(): \DateTime {
		return $this->updated;
	}

	/**
	 * Add keywordTranslation
	 *
	 * @param \Rapsys\BlogBundle\Entity\KeywordTranslation $keywordTranslation
	 *
	 * @return Keyword
	 */
	public function addKeywordTranslation(KeywordTranslation $keywordTranslation): Keyword {
		$this->keyword_translations[] = $keywordTranslation;

		return $this;
	}

	/**
	 * Remove keywordTranslation
	 *
	 * @param \Rapsys\BlogBundle\Entity\KeywordTranslation $keywordTranslation
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function removeKeywordTranslation(KeywordTranslation $keywordTranslation): Collection {
		return $this->keyword_translations->removeElement($keywordTranslation);
	}

	/**
	 * Get keywordTranslations
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getKeywordTranslations(): Collection {
		return $this->keyword_translations;
	}

	/**
	 * Add article
	 *
	 * @param \Rapsys\BlogBundle\Entity\Article $article
	 *
	 * @return Keyword
	 */
	public function addArticle(Article $article): Keyword {
		$this->articles[] = $article;

		return $this;
	}

	/**
	 * Remove article
	 *
	 * @param \Rapsys\BlogBundle\Entity\Article $article
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function removeArticle(Article $article): Collection {
		return $this->articles->removeElement($article);
	}

	/**
	 * Get articles
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getArticles(): Collection {
		return $this->articles;
	}

	/**
	 * {@inheritdoc}
	 */
	public function preUpdate(PreUpdateEventArgs $eventArgs): ?Keyword {
		//Check that we have an snippet instance
		if (($entity = $eventArgs->getEntity()) instanceof Keyword) {
			//Set updated value
			return $entity->setUpdated(new \DateTime('now'));
		}
	}
}
