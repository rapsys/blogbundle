<?php declare(strict_types=1);

/*
 * This file is part of the Rapsys BlogBundle package.
 *
 * (c) Raphaël Gertz <symfony@rapsys.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rapsys\BlogBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository as BaseEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use Rapsys\PackBundle\Util\SluggerUtil;

/**
 * EntityRepository
 *
 * {@inheritdoc}
 */
class EntityRepository extends BaseEntityRepository {
	/**
	 * The RouterInterface instance
	 *
	 * @var RouterInterface
	 */
	protected RouterInterface $router;

	/**
	 * The SluggerUtil instance
	 *
	 * @var SluggerUtil
	 */
	protected SluggerUtil $slugger;

	/**
	 * The table keys array
	 *
	 * @var array
	 */
	protected array $tableKeys;

	/**
	 * The table values array
	 *
	 * @var array
	 */
	protected array $tableValues;

	/**
	 * The TranslatorInterface instance
	 *
	 * @var TranslatorInterface
	 */
	protected TranslatorInterface $translator;

	/**
	 * The list of languages
	 *
	 * @var string[]
	 */
	protected array $languages = [];

	/**
	 * The current locale
	 *
	 * @var string
	 */
	protected string $locale;

	/**
	 * Initializes a new LocationRepository instance
	 *
	 * @param EntityManagerInterface $manager The EntityManagerInterface instance
	 * @param ClassMetadata $class The ClassMetadata instance
	 * @param RouterInterface $router The router instance
	 * @param SluggerUtil $slugger The SluggerUtil instance
	 * @param TranslatorInterface $translator The TranslatorInterface instance
	 * @param array $languages The languages list
	 * @param string $locale The current locale
	 */
	public function __construct(EntityManagerInterface $manager, ClassMetadata $class, RouterInterface $router, SluggerUtil $slugger, TranslatorInterface $translator, array $languages, string $locale) {
		//Call parent constructor
		parent::__construct($manager, $class);

		//Set languages
		$this->languages = $languages;

		//Set locale
		$this->locale = $locale;

		//Set router
		$this->router = $router;

		//Set slugger
		$this->slugger = $slugger;

		//Set translator
		$this->translator = $translator;

		//Get quote strategy
		$qs = $manager->getConfiguration()->getQuoteStrategy();
		$dp = $manager->getConnection()->getDatabasePlatform();

		//Set quoted table names
		//XXX: this allow to make this code table name independent
		//XXX: remember to place longer prefix before shorter to avoid strange replacings
		$tables = [
			//Set entities
			'RapsysBlogBundle:ArticleKeyword' => $qs->getJoinTableName($manager->getClassMetadata('Rapsys\BlogBundle\Entity\Article')->getAssociationMapping('keywords'), $manager->getClassMetadata('Rapsys\BlogBundle\Entity\Article'), $dp),
			'RapsysBlogBundle:ArticleTranslation' => $qs->getTableName($manager->getClassMetadata('Rapsys\BlogBundle\Entity\ArticleTranslation'), $dp),
			'RapsysBlogBundle:KeywordTranslation' => $qs->getTableName($manager->getClassMetadata('Rapsys\BlogBundle\Entity\KeywordTranslation'), $dp),
			'RapsysBlogBundle:UserTranslation' => $qs->getTableName($manager->getClassMetadata('Rapsys\BlogBundle\Entity\UserTranslation'), $dp),
			'RapsysBlogBundle:Article' => $qs->getTableName($manager->getClassMetadata('Rapsys\BlogBundle\Entity\Article'), $dp),
			'RapsysBlogBundle:Civility' => $qs->getTableName($manager->getClassMetadata('Rapsys\BlogBundle\Entity\Civility'), $dp),
			'RapsysBlogBundle:Group' => $qs->getTableName($manager->getClassMetadata('Rapsys\BlogBundle\Entity\Group'), $dp),
			'RapsysBlogBundle:Keyword' => $qs->getTableName($manager->getClassMetadata('Rapsys\BlogBundle\Entity\Keyword'), $dp),
			'RapsysBlogBundle:User' => $qs->getTableName($manager->getClassMetadata('Rapsys\BlogBundle\Entity\User'), $dp),
			//Set locale
			//XXX: or $manager->getConnection()->quote($this->locale) ???
			':locale' => $dp->quoteStringLiteral($this->locale),
			//Set limit
			//XXX: Set limit used to workaround mariadb subselect optimization
			':limit' => PHP_INT_MAX,
			//Set cleanup
			"\t" => '',
			"\n" => ' '
		];

		//Set quoted table name keys
		$this->tableKeys = array_keys($tables);

		//Set quoted table name values
		$this->tableValues = array_values($tables);
	}

	/**
	 * Get replaced query
	 *
	 * @param string $req The request to replace
	 * @return string The replaced request
	 */
	protected function replace(string $req): string {
		//Replace bundle entity name by table name
		return str_replace($this->tableKeys, $this->tableValues, $req);
	}
}
