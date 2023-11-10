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
 * UserTranslation
 */
class UserTranslation {
	/**
	 * @var int
	 */
	private int $user_id;

	/**
	 * @var string
	 */
	private string $locale;

	/**
	 * @var string
	 */
	private ?string $description;

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
	 * Constructor
	 */
	public function __construct(User $user, string $locale, ?string $description = null) {
		//Set defaults
		$this->locale = $locale;
		$this->description = $description;
		$this->created = new \DateTime('now');
		$this->updated = new \DateTime('now');
		$this->setUser($user);
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
	 * @return UserTranslation
	 */
	public function setLocale(string $locale): UserTranslation {
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
	 * @return UserTranslation
	 */
	public function setDescription(?string $description): UserTranslation {
		$this->description = $description;

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
	 * @return UserTranslation
	 */
	public function setCreated(\DateTime $created): UserTranslation {
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
	 * @return UserTranslation
	 */
	public function setUpdated(\DateTime $updated): UserTranslation {
		$this->updated = $updated;

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
	 * Set user
	 *
	 * @param \Rapsys\BlogBundle\Entity\User $user
	 *
	 * @return UserTranslation
	 */
	public function setUser(User $user): UserTranslation {
		$this->user = $user;
		$this->user_id = $user->getId();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function preUpdate(PreUpdateEventArgs $eventArgs): ?UserTranslation {
		//Check that we have an snippet instance
		if (($entity = $eventArgs->getEntity()) instanceof UserTranslation) {
			//Set updated value
			return $entity->setUpdated(new \DateTime('now'));
		}
	}
}
