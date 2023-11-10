<?php declare(strict_types=1);

/*
 * This file is part of the Rapsys BlogBundle package.
 *
 * (c) Raphaël Gertz <symfony@rapsys.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rapsys\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Rapsys\BlogBundle\Entity\Article;
use Rapsys\BlogBundle\Entity\Keyword;

/**
 * {@inheritdoc}
 */
class ArticleController extends AbstractController {
	/**
	 * The article index
	 *
	 * Display articles
	 *
	 * @param Request $request The request instance
	 * @return Response The rendered view
	 */
	public function index(Request $request): Response {
		//With articles
		if ($count = $this->doctrine->getRepository(Article::class)->findCountAsInt()) {
			//Negative page or over page
			if (($page = (int) $request->get('page', 0)) < 0 || $page > $count / $this->limit) {
				//Throw 404
				throw $this->createNotFoundException($this->translator->trans('Unable to find articles (page: %page%)', ['%page%' => $page]));
			}

			//Without articles
			if (empty($this->context['articles'] = $this->doctrine->getRepository(Article::class)->findAllAsArray($page, $this->limit))) {
				//Throw 404
				throw $this->createNotFoundException($this->translator->trans('Unable to find articles'));
			}

			//With prev link
			if ($page > 0) {
				//Set articles older
				$this->context['head']['prev'] = $this->context['articles_prev'] = $this->generateUrl($request->attributes->get('_route'), ['page' => $page - 1]+$request->attributes->get('_route_params'));
			}

			//With next link
			if ($count > ($page + 1) * $this->limit) {
				//Set articles newer
				$this->context['head']['next'] = $this->context['articles_next'] = $this->generateUrl($request->attributes->get('_route'), ['page' => $page + 1]+$request->attributes->get('_route_params'));
			}

			//Set modified
			$this->modified = max(array_map(function ($v) { return $v['modified']; }, $this->context['articles']));
		//Without articles
		} else {
			//Set empty articles
			$this->context['articles'] = [];

			//Set empty modified
			$this->modified = new \DateTime('-1 year');
		}

		//Create response
		$response = new Response();

		//With logged user
		if ($this->checker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			//Set last modified
			$response->setLastModified(new \DateTime('-1 year'));

			//Set as private
			$response->setPrivate();
		//Without logged user
		} else {
			//Set etag
			//XXX: only for public to force revalidation by last modified
			$response->setEtag(md5(serialize($this->context['articles'])));

			//Set last modified
			$response->setLastModified($this->modified);

			//Set as public
			$response->setPublic();

			//Without role and modification
			if ($response->isNotModified($request)) {
				//Return 304 response
				return $response;
			}
		}

		//Set keywords
		$this->context['head']['keywords'] = implode(
			', ',
			//Use closure to extract each unique article keywords sorted
			(function ($t) {
				//Return array
				$r = [];

				//Iterate on articles
				foreach($t as $a) {
					//Non empty keywords
					if (!empty($a['keywords'])) {
						//Iterate on keywords
						foreach($a['keywords'] as $k) {
							//Set keyword
							$r[$k['title']] = $k['title'];
						}
					}
				}

				//Sort array
				sort($r);

				//Return array
				return $r;
			})($this->context['articles'])
		);

		//Set title
		$this->context['title'] = $this->translator->trans('Articles list');

		//Set description
		$this->context['description'] = $this->translator->trans('Welcome to raphaël\'s developer diary article listing');

		//Render the view
		return $this->render('@RapsysBlog/article/index.html.twig', $this->context, $response);
	}

	/**
	 * The article view
	 *
	 * Display article and keywords
	 *
	 * @param Request $request The request instance
	 * @param integer $id The article id
	 * @param ?string $slug The article slug
	 * @return Response The rendered view
	 */
	public function view(Request $request, int $id, ?string $slug): Response {
		//Without article
		if (empty($this->context['article'] = $this->doctrine->getRepository(Article::class)->findByIdAsArray($id))) {
			//Throw 404
			throw $this->createNotFoundException($this->translator->trans('Unable to find article: %id%', ['%id%' => $id]));
		}

		//With invalid slug
		if ($slug !== $this->context['article']['slug']) {
			//Redirect on correctly spelled article
			return $this->redirectToRoute('rapsys_blog_article_view', ['id' => $this->context['article']['id'], 'slug' => $this->context['article']['slug']], Response::HTTP_MOVED_PERMANENTLY);
		}

		//Set modified
		$this->modified = $this->context['article']['modified'];

		//Create response
		$response = new Response();

		//With logged user
		if ($this->checker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			//Set last modified
			$response->setLastModified(new \DateTime('-1 year'));

			//Set as private
			$response->setPrivate();
		//Without logged user
		} else {
			//Set etag
			//XXX: only for public to force revalidation by last modified
			$response->setEtag(md5(serialize($this->context['article'])));

			//Set last modified
			$response->setLastModified($this->modified);

			//Set as public
			$response->setPublic();

			//Without role and modification
			if ($response->isNotModified($request)) {
				//Return 304 response
				return $response;
			}
		}

		//Set keywords
		$this->context['head']['keywords'] = implode(
			', ',
			array_map(
				function ($v) {
					return $v['title'];
				},
				$this->context['article']['keywords']
			)
		);

		//Set keywords
		$this->context['head']['keywords'] = implode(
			', ',
			//Use closure to extract each unique article keywords sorted
			(function ($a) {
				//Return array
				$r = [];

				//Non empty keywords
				if (!empty($a['keywords'])) {
					//Iterate on keywords
					foreach($a['keywords'] as $k) {
						//Set keyword
						$r[$k['title']] = $k['title'];
					}
				}

				//Sort array
				sort($r);

				//Return array
				return $r;
			})($this->context['article'])
		);

		//Set title
		$this->context['title'] = $this->context['article']['title'];

		//Set description
		$this->context['description'] = $this->context['article']['description'];

		//Render the view
		return $this->render('@RapsysBlog/article/view.html.twig', $this->context, $response);
	}
}
