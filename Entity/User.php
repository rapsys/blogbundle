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

use Rapsys\UserBundle\Entity\User as BaseUser;

class User extends BaseUser {
	/**
	 * @var ?string
	 */
	protected ?string $pseudonym;

	/**
	 * @var ?string
	 */
	protected ?string $slug;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private Collection $articles;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private Collection $user_translations;

	/**
	 * Constructor
	 *
	 * @param string $mail The user mail
	 * @param string $password The user password
	 * @param ?Civility $civility The user civility
	 * @param ?string $forename The user forename
	 * @param ?string $surname The user surname
	 * @param bool $active The user is active
	 * @param bool $enable The user is enable
	 * @param ?string $pseudonym The user pseudonym
	 * @param ?string $slug The user slug
	 */
	public function __construct(string $mail, string $password, ?Civility $civility = null, ?string $forename = null, ?string $surname = null, bool $active = false, bool $enable = true, ?string $pseudonym = null, ?string $slug = null) {
		//Call parent constructor
		parent::__construct($mail, $password, $civility, $forename, $surname, $active, $enable);

		//Set defaults
		$this->pseudonym = $pseudonym;
		$this->slug = $slug;

		//Set collections
		$this->articles = new ArrayCollection();
		$this->user_translations = new ArrayCollection();
	}

	/**
	 * Set pseudonym
	 *
	 * @param ?string $pseudonym
	 *
	 * @return User
	 */
	public function setPseudonym(?string $pseudonym = null): User {
		$this->pseudonym = $pseudonym;

		return $this;
	}

	/**
	 * Get pseudonym
	 *
	 * @return ?string
	 */
	public function getPseudonym(): ?string {
		return $this->pseudonym;
	}

	/**
	 * Set slug
	 *
	 * @param ?string $slug
	 *
	 * @return User
	 */
	public function setSlug(?string $slug = null): User {
		$this->slug = $slug;

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
	 * Add article
	 *
	 * @param Article $article
	 *
	 * @return User
	 */
	public function addArticle(Article $article): User {
		$this->articles[] = $article;

		return $this;
	}

	/**
	 * Remove article
	 *
	 * @param Article $article
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
	 * Add user translation
	 *
	 * @param \Rapsys\BlogBundle\Entity\UserTranslation $userTranslation
	 *
	 * @return User
	 */
	public function addUserTranslation(UserTranslation $userTranslation): User {
		$this->user_translations[] = $userTranslation;

		return $this;
	}

	/**
	 * Remove user translation
	 *
	 * @param \Rapsys\BlogBundle\Entity\UserTranslation $userTranslation
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function removeUserTranslation(UserTranslation $userTranslation): Collection {
		return $this->user_translations->removeElement($userTranslation);
	}

	/**
	 * Get user translations
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getUserTranslations(): Collection {
		return $this->user_translations;
	}
}
