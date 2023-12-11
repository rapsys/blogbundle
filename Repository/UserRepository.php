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

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository implements PasswordUpgraderInterface {
	/**
	 * Find user count as int
	 *
	 * @return integer The keywords count
	 */
	public function findCountAsInt(): int {
		//Set the request
		$req = <<<SQL
SELECT COUNT(u.id) AS count
FROM RapsysBlogBundle:User AS u
SQL;

		//Get result set mapping instance
		$req = $this->replace($req);

		//Get result set mapping instance
		//XXX: DEBUG: see ../blog.orig/src/Rapsys/BlogBundle/Repository/ArticleRepository.php
		$rsm = new ResultSetMapping();

		//Declare all fields
		//XXX: see vendor/doctrine/dbal/lib/Doctrine/DBAL/Types/Types.php
		//addScalarResult($sqlColName, $resColName, $type = 'string');
		$rsm->addScalarResult('count', 'count', 'integer');

		//Get result
		return $this->_em
			->createNativeQuery($req, $rsm)
			->getSingleScalarResult();
	}

	/**
	 * Find all users as array
	 *
	 * @param integer $page The page
	 * @param integer $count The count
	 * @return array The users sorted by id
	 */
	public function findAllAsArray(int $page, int $count): array {
		//Set the request
		$req = <<<SQL
SELECT
	t.id,
	t.mail,
	t.forename,
	t.surname,
	t.pseudonym,
	t.slug,
	t.civility,
	t.g_ids,
	t.g_titles,
	GROUP_CONCAT(t.a_id ORDER BY t.a_id SEPARATOR "\\n") AS a_ids,
	GROUP_CONCAT(t.at_description ORDER BY t.a_id SEPARATOR "\\n") AS at_descriptions,
	GROUP_CONCAT(t.at_slug ORDER BY t.a_id SEPARATOR "\\n") AS at_slugs,
	GROUP_CONCAT(t.at_title ORDER BY t.a_id SEPARATOR "\\n") AS at_titles,
	GROUP_CONCAT(t.ak_ids ORDER BY t.a_id SEPARATOR "\\n") AS ak_ids,
	GROUP_CONCAT(t.kt_slugs ORDER BY t.a_id SEPARATOR "\\n") AS kt_slugs,
	GROUP_CONCAT(t.kt_titles ORDER BY t.a_id SEPARATOR "\\n") AS kt_titles
FROM (
	SELECT
		c.id,
		c.mail,
		c.forename,
		c.surname,
		c.pseudonym,
		c.slug,
		c.civility,
		c.g_ids,
		c.g_titles,
		a.id AS a_id,
		at.description AS at_description,
		at.slug AS at_slug,
		at.title AS at_title,
		GROUP_CONCAT(ak.keyword_id ORDER BY ak.keyword_id SEPARATOR "\\r") AS ak_ids,
		GROUP_CONCAT(kt.slug ORDER BY ak.keyword_id SEPARATOR "\\r") AS kt_slugs,
		GROUP_CONCAT(kt.title ORDER BY ak.keyword_id SEPARATOR "\\r") AS kt_titles
	FROM (
		SELECT
			u.id,
			u.mail,
			u.forename,
			u.surname,
			u.pseudonym,
			u.slug,
			c.title AS civility,
			GROUP_CONCAT(g.id ORDER BY g.id SEPARATOR "\\n") AS g_ids,
			GROUP_CONCAT(g.title ORDER BY g.id SEPARATOR "\\n") AS g_titles
		FROM RapsysBlogBundle:User AS u
		JOIN RapsysBlogBundle:UserGroup AS gu ON (gu.user_id = u.id)
		JOIN RapsysBlogBundle:Group AS g ON (g.id = gu.group_id)
		JOIN RapsysBlogBundle:Civility AS c ON (c.id = u.civility_id)
		ORDER BY NULL
		LIMIT 0, :limit
	) AS c
	LEFT JOIN RapsysBlogBundle:Article AS a ON (a.user_id = c.id)
	LEFT JOIN RapsysBlogBundle:ArticleTranslation AS at ON (at.article_id = a.id AND at.locale = :locale)
	LEFT JOIN RapsysBlogBundle:ArticleKeyword AS ak ON (ak.article_id = a.id)
	LEFT JOIN RapsysBlogBundle:KeywordTranslation AS kt ON (kt.keyword_id = ak.keyword_id AND at.locale = :locale)
	GROUP BY a.id
	ORDER BY NULL
	LIMIT 0, :limit
) AS t
GROUP BY t.id
ORDER BY t.id ASC
LIMIT :offset, :count
SQL;

		//Replace bundle entity name by table name
		$req = $this->replace($req);

		//Get result set mapping instance
		//XXX: DEBUG: see ../blog.orig/src/Rapsys/UserBundle/Repository/ArticleRepository.php
		$rsm = new ResultSetMapping();

		//Declare all fields
		//XXX: see vendor/doctrine/dbal/lib/Doctrine/DBAL/Types/Types.php
		//addScalarResult($sqlColName, $resColName, $type = 'string');
		$rsm->addScalarResult('id', 'id', 'integer')
			->addScalarResult('mail', 'mail', 'string')
			->addScalarResult('forename', 'forename', 'string')
			->addScalarResult('surname', 'surname', 'string')
			->addScalarResult('pseudonym', 'pseudonym', 'string')
			->addScalarResult('slug', 'slug', 'string')
			->addScalarResult('civility', 'civility', 'string')
			//XXX: is a string because of \n separator
			->addScalarResult('g_ids', 'g_ids', 'string')
			//XXX: is a string because of \n separator
			->addScalarResult('g_titles', 'g_titles', 'string')
			//XXX: is a string because of \n separator
			->addScalarResult('a_ids', 'a_ids', 'string')
			//XXX: is a string because of \n separator
			->addScalarResult('at_descriptions', 'at_descriptions', 'string')
			//XXX: is a string because of \n separator
			->addScalarResult('at_slugs', 'at_slugs', 'string')
			//XXX: is a string because of \n separator
			->addScalarResult('at_titles', 'at_titles', 'string')
			//XXX: is a string because of \n separator
			->addScalarResult('ak_ids', 'ak_ids', 'string')
			//XXX: is a string because of \n separator
			->addScalarResult('kt_slugs', 'kt_slugs', 'string')
			//XXX: is a string because of \n separator
			->addScalarResult('kt_titles', 'kt_titles', 'string');

		//Fetch result
		$res = $this->_em
			->createNativeQuery($req, $rsm)
			->setParameter('offset', $page * $count)
			->setParameter('count', $count)
			->getResult();

		//Init return
		$ret = [];

		//Process result
		foreach($res as $data) {
			//Set data
			$ret[$data['id']] = [
				'mail' => $data['mail'],
				'forename' => $data['forename'],
				'surname' => $data['surname'],
				'pseudonym' => $data['pseudonym'],
				#'slug' => $data['slug'],
				'link' => $this->router->generate('rapsys_blog_user_view', ['id' => $data['id'], 'slug' => $data['slug']]),
				'edit' => $this->router->generate('rapsys_user_edit', ['mail' => $short = $this->slugger->short($data['mail']), 'hash' => $this->slugger->hash($short)]),
				'articles' => [],
				'groups' => []
			];

			//With groups
			if (!empty($data['g_ids'])) {
				//Set titles
				$titles = explode("\n", $data['g_titles']);

				//Iterate on each group
				foreach(explode("\n", $data['g_ids']) as $k => $id) {
					//Add group
					$ret[$data['id']]['groups'][$id] = [
						'title' => /*$group = */$this->translator->trans($titles[$k]),
						#'slug' => $this->slugger->slug($group)
						#'link' => $this->router->generate('rapsys_user_group_view', ['id' => $id, 'slug' => $this->slugger->short($group)])
					];
				}
			}

			//With articles
			if (!empty($data['a_ids'])) {
				//Set descriptions
				$descriptions = explode("\n", $data['at_descriptions']);

				//Set slugs
				$slugs = explode("\n", $data['at_slugs']);

				//Set titles
				$titles = explode("\n", $data['at_titles']);

				//Set keyword ids
				$keywords = [
					'ids' => explode("\n", $data['ak_ids']),
					'slugs' => explode("\n", $data['kt_slugs']),
					'titles' => explode("\n", $data['kt_titles'])
				];

				//Iterate on each dance
				foreach(explode("\n", $data['a_ids']) as $k => $id) {
					//Init article when missing
					if (!isset($ret[$data['id']]['articles'][$id])) {
						//Add article
						$ret[$data['id']]['articles'][$id] = [
							'description' => $descriptions[$k],
							#'slug' => $slugs[$k],
							'title' => $titles[$k],
							'link' => $this->router->generate('rapsys_blog_article_view', ['id' => $id, 'slug' => $slugs[$k]]),
							//TODO: replace with keywords !!!
							'keywords' => []
						];

						//With article keywords
						if (!empty($keywords['ids'][$k])) {
							//Set slugs
							$slugs = explode("\r", $keywords['slugs'][$k]);

							//Set titles
							$titles = explode("\r", $keywords['titles'][$k]);

							//Iterate on each keyword
							foreach(explode("\r", $keywords['ids'][$k]) as $k => $kid) {
								//Add keyword
								$ret[$data['id']]['articles'][$id]['keywords'][$kid] = [
									#'slug' => $slugs[$k],
									'title' => $titles[$k],
									'link' => $this->router->generate('rapsys_blog_keyword_view', ['id' => $kid, 'slug' => $slugs[$k]]),
								];
							}
						}
					}
				}
			}
		}

		//Send result
		return $ret;
	}

	/**
	 * {@inheritdoc}
	 */
	public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $hash): void {
		//Set new hashed password
		$user->setPassword($hash);

		//Flush data to database
		$this->getEntityManager()->flush();
	}
}
