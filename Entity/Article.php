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
 * Article
 */
class Article {
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
	 * @var \Rapsys\BlogBundle\Entity\User
	 */
	private User $user;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private Collection $article_translations;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private Collection $keywords;

	/**
	 * Constructor
	 *
	 * @param \Rapsys\BlogBundle\Entity\User $user
	 */
	public function __construct(User $user) {
		$this->created = new \DateTime('now');
		$this->updated = new \DateTime('now');
		$this->user = $user;
		$this->article_translations = new ArrayCollection();
		$this->keywords = new ArrayCollection();
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
	 * @return Article
	 */
	public function setCreated(\DateTime $created): Article {
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
	 * @return Article
	 */
	public function setUpdated(\DateTime $updated): Article {
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
	 * Set user
	 *
	 * @param \Rapsys\BlogBundle\Entity\User $user
	 *
	 * @return Article
	 */
	public function setUser(User $user): Article {
		$this->user = $user;

		return $this;
	}

	/**
	 * Get user
	 *
	 * @return \Rapsys\BlogBundle\Entity\User
	 */
	public function getUser(): User {
		return $this->user;
	}

	/**
	 * Add article translation
	 *
	 * @param \Rapsys\BlogBundle\Entity\ArticleTranslation $articleTranslation
	 *
	 * @return Article
	 */
	public function addArticleTranslation(ArticleTranslation $articleTranslation): Article {
		$this->article_translations[] = $articleTranslation;

		return $this;
	}

	/**
	 * Remove article translation
	 *
	 * @param \Rapsys\BlogBundle\Entity\ArticleTranslation $articleTranslation
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function removeArticleTranslation(ArticleTranslation $articleTranslation): Collection {
		return $this->article_translations->removeElement($articleTranslation);
	}

	/**
	 * Get article translations
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getArticleTranslations(): Collection {
		return $this->article_translations;
	}

	/**
	 * Add keyword
	 *
	 * @param \Rapsys\BlogBundle\Entity\Keyword $keyword
	 *
	 * @return Article
	 */
	public function addKeyword(Keyword $keyword): Article {
		$this->keywords[] = $keyword;

		return $this;
	}

	/**
	 * Remove keyword
	 *
	 * @param \Rapsys\BlogBundle\Entity\Keyword $keyword
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function removeKeyword(Keyword $keyword): Collection {
		return $this->keywords->removeElement($keyword);
	}

	/**
	 * Get keywords
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getKeywords(): Collection {
		return $this->keywords;
	}

	/**
	 * {@inheritdoc}
	 */
	public function preUpdate(PreUpdateEventArgs $eventArgs): ?Article {
		//Check that we have an snippet instance
		if (($entity = $eventArgs->getEntity()) instanceof Article) {
			//Set updated value
			return $entity->setUpdated(new \DateTime('now'));
		}
	}
}
