<?php

namespace Rapsys\BlogBundle\Repository;

/**
 * KeywordRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class KeywordRepository extends \Doctrine\ORM\EntityRepository {
	/**
	 * Find keyword
	 *
	 * @param string $_locale
	 * @param string $_keyword
	 */
	public function findKeyword($_locale, $_keyword) {
		//Fetch keyword
		$ret = $this->getEntityManager()
			->createQuery('SELECT k.id, k.created, k.updated, kt.slug, kt.title, kt.description, JSON(at.slug, at.title) AS articles, \'\' AS slugs FROM RapsysBlogBundle:Keyword k JOIN RapsysBlogBundle:KeywordTranslation kt WITH (kt.keyword_id = k.id AND kt.slug = :_keyword) JOIN RapsysBlogBundle:Language AS l WITH (l.id = kt.language_id AND l.iso6391 = :_locale) LEFT JOIN RapsysBlogBundle:ArticleKeyword ak WITH (ak.keyword_id = k.id) LEFT JOIN RapsysBlogBundle:ArticleTranslation at WITH (at.article_id = ak.article_id AND at.language_id = kt.language_id) GROUP BY k.id')
			->setParameter('_locale', $_locale)
			->setParameter('_keyword', $_keyword)
			->getSingleResult();

		//Decode json
		if (!empty($ret['articles'])) {
			$ret['articles'] = json_decode($ret['articles'], true);
		}

		//Fetch keyword's slugs in other locale
		$slugs = $this->getEntityManager()
			->createQuery('SELECT JSON(bl.iso6391, bkt.slug) AS slugs FROM RapsysBlogBundle:Language bl LEFT JOIN RapsysBlogBundle:KeywordTranslation bkt WITH (bkt.keyword_id = :_keyword AND bkt.language_id = bl.id) WHERE (bl.iso6391 <> :_locale) GROUP BY bkt.keyword_id')
			->setParameter('_locale', $_locale)
			->setParameter('_keyword', $ret['id'])
			->getSingleResult();

		//Decode json
		if (!empty($slugs['slugs'])) {
			$ret['slugs'] = json_decode($slugs['slugs'], true);
		}

		//Send result
		return $ret;
	}

	/**
	 * Find keywords
	 *
	 * @param string $_locale
	 */
	public function findKeywords($_locale) {
		//Fetch keywords
		$ret = $this->getEntityManager()
			#, JSON(kt.keyword_id, kt.title) AS keywords
                        ->createQuery('SELECT k.id, k.created, k.updated, kt.slug, kt.title, JSON(at.slug, at.title) AS articles FROM RapsysBlogBundle:Keyword k JOIN RapsysBlogBundle:KeywordTranslation kt WITH (kt.keyword_id = k.id) JOIN RapsysBlogBundle:Language AS l WITH (l.id = kt.language_id AND l.iso6391 = :_locale) LEFT JOIN RapsysBlogBundle:ArticleKeyword ak WITH (ak.keyword_id = k.id) LEFT JOIN RapsysBlogBundle:ArticleTranslation at WITH (at.article_id = ak.article_id AND at.language_id = kt.language_id) GROUP BY k.id')
                        ->setParameter('_locale', $_locale)
			->execute();
		//Decode json
		if (!empty($ret)) {
			foreach ($ret as $id => $keyword) {
				if (!empty($keyword['articles'])) {
					$ret[$id]['articles'] = json_decode($keyword['articles'], true);
				}
			}
		}
		//Send result
		return $ret;
	}
}