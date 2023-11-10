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
 * KeywordTranslation
 */
class KeywordTranslation {
	/**
	 * @var int
	 */
	private int $keyword_id;

	/**
	 * @var string
	 */
	private string $locale;

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
	 * @var \Rapsys\BlogBundle\Entity\Keyword
	 */
	private Keyword $keyword;

	/**
	 * Constructor
	 */
	public function __construct(Keyword $keyword, string $locale, ?string $description = null, ?string $slug = null, ?string $title = null) {
		//Set defaults
		$this->locale = $locale;
		$this->description = $description;
		$this->slug = $slug;
		$this->title = $title;
		$this->created = new \DateTime('now');
		$this->updated = new \DateTime('now');
		$this->setKeyword($keyword);
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
	 * @return KeywordTranslation
	 */
	public function setLocale(string $locale): KeywordTranslation {
		$this->locale = $locale;

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
	 * @return KeywordTranslation
	 */
	public function setDescription(?string $description): KeywordTranslation {
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
	 * @return KeywordTranslation
	 */
	public function setSlug(?string $slug): KeywordTranslation {
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
	 * @return KeywordTranslation
	 */
	public function setTitle(?string $title): KeywordTranslation {
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
	 * @return KeywordTranslation
	 */
	public function setCreated(\DateTime $created): KeywordTranslation {
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
	 * @return KeywordTranslation
	 */
	public function setUpdated(\DateTime $updated): KeywordTranslation {
		$this->updated = $updated;

		return $this;
	}

	/**
	 * Get keyword
	 *
	 * @return \Rapsys\BlogBundle\Entity\Keyword
	 */
	public function getKeyword(): Keyword {
		return $this->keyword;
	}

	/**
	 * Set keyword
	 *
	 * @param \Rapsys\BlogBundle\Entity\Keyword $keyword
	 *
	 * @return KeywordTranslation
	 */
	public function setKeyword(Keyword $keyword): KeywordTranslation {
		$this->keyword = $keyword;
		$this->keyword_id = $keyword->getId();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function preUpdate(PreUpdateEventArgs $eventArgs): ?KeywordTranslation {
		//Check that we have an snippet instance
		if (($entity = $eventArgs->getEntity()) instanceof KeywordTranslation) {
			//Set updated value
			return $entity->setUpdated(new \DateTime('now'));
		}
	}
}
