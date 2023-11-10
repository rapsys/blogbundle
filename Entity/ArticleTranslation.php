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

use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * ArticleTranslation
 */
class ArticleTranslation {
	/**
	 * @var int
	 */
	private int $article_id;

	/**
	 * @var string
	 */
	private string $locale;

	/**
	 * @var ?string
	 */
	private ?string $body;

	/**
	 * @var ?string
	 */
	private ?string $description;

	/**
	 * @var ?string
	 */
	private ?string $slug;

	/**
	 * @var ?string
	 */
	private ?string $title;

	/**
	 * @var \DateTime
	 */
	private \DateTime $created;

	/**
	 * @var \DateTime
	 */
	private \DateTime $updated;

	/**
	 * @var \Rapsys\BlogBundle\Entity\Article
	 */
	private Article $article;

	/**
	 * Constructor
	 */
	public function __construct(Article $article, string $locale, ?string $body = null, ?string $description = null, ?string $slug = null, ?string $title = null) {
		//Set defaults
		$this->locale = $locale;
		$this->body = $body;
		$this->description = $description;
		$this->slug = $slug;
		$this->title = $title;
		$this->created = new \DateTime('now');
		$this->updated = new \DateTime('now');
		$this->setArticle($article);
	}

	/**
	 * Get locale
	 *
	 * @return string
	 */
	public function getLocale(): string {
		return $this->locale;
	}

	/**
	 * Set locale
	 *
	 * @param string $locale
	 *
	 * @return ArticleTranslation
	 */
	public function setLocale(string $locale): ArticleTranslation {
		$this->locale = $locale;

		return $this;
	}

	/**
	 * Get body
	 *
	 * @return ?string
	 */
	public function getBody(): ?string {
		return $this->body;
	}

	/**
	 * Set body
	 *
	 * @param ?string $body
	 *
	 * @return ArticleTranslation
	 */
	public function setBody(?string $body): ArticleTranslation {
		$this->body = $body;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return ?string
	 */
	public function getDescription(): ?string {
		return $this->description;
	}

	/**
	 * Set description
	 *
	 * @param ?string $description
	 *
	 * @return ArticleTranslation
	 */
	public function setDescription(?string $description): ArticleTranslation {
		$this->description = $description;

		return $this;
	}

	/**
	 * Get slug
	 *
	 * @return ?string
	 */
	public function getSlug(): ?string {
		return $this->slug;
	}

	/**
	 * Set slug
	 *
	 * @param ?string $slug
	 *
	 * @return ArticleTranslation
	 */
	public function setSlug(?string $slug): ArticleTranslation {
		$this->slug = $slug;

		return $this;
	}

	/**
	 * Get title
	 *
	 * @return ?string
	 */
	public function getTitle(): ?string {
		return $this->title;
	}

	/**
	 * Set title
	 *
	 * @param ?string $title
	 *
	 * @return ArticleTranslation
	 */
	public function setTitle(?string $title): ArticleTranslation {
		$this->title = $title;

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
	 * Set created
	 *
	 * @param \DateTime $created
	 *
	 * @return ArticleTranslation
	 */
	public function setCreated(\DateTime $created): ArticleTranslation {
		$this->created = $created;

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
	 * Set updated
	 *
	 * @param \DateTime $updated
	 *
	 * @return ArticleTranslation
	 */
	public function setUpdated(\DateTime $updated): ArticleTranslation {
		$this->updated = $updated;

		return $this;
	}

	/**
	 * Get article
	 *
	 * @return \Rapsys\BlogBundle\Entity\Article
	 */
	public function getArticle(): Article {
		return $this->article;
	}

	/**
	 * Set article
	 *
	 * @param \Rapsys\BlogBundle\Entity\Article $article
	 *
	 * @return ArticleTranslation
	 */
	public function setArticle(Article $article): ArticleTranslation {
		$this->article = $article;
		$this->article_id = $article->getId();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function preUpdate(PreUpdateEventArgs $eventArgs): ?ArticleTranslation {
		//Check that we have an snippet instance
		if (($entity = $eventArgs->getEntity()) instanceof ArticleTranslation) {
			//Set updated value
			return $entity->setUpdated(new \DateTime('now'));
		}
	}
}
