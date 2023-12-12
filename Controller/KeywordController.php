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
class KeywordController extends AbstractController {
	/**
	 * The keyword index
	 *
	 * Display keywords
	 *
	 * @param Request $request The request instance
	 * @return Response The rendered view
	 */
	public function index(Request $request): Response {
		//With not enough keywords
		if (($this->count = $this->doctrine->getRepository(Keyword::class)->findCountAsInt()) < $this->page * $this->limit) {
			//Throw 404
			throw $this->createNotFoundException($this->translator->trans('Unable to find keywords'));
		}

		//Get keywords
		if ($this->context['keywords'] = $this->doctrine->getRepository(Keyword::class)->findAllAsArray($this->page, $this->limit)) {
			//Set modified
			$this->modified = max(array_map(function ($v) { return $v['modified']; }, $this->context['keywords']));
		//Without keywords
		} else {
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
			$response->setEtag(md5(serialize($this->context['keywords'])));

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

				//Iterate on keywords
				foreach($t as $k) {
					//Set keyword
					$r[$k['title']] = $k['title'];
				}

				//Sort array
				sort($r);

				//Return array
				return $r;
			})($this->context['keywords'])
		);

		//Set title
		$this->context['title'] = $this->translator->trans('Keywords list');

		//Set description
		$this->context['description'] = $this->translator->trans('Welcome to raphaël\'s developer diary keyword listing');

		//Render the view
		return $this->render('@RapsysBlog/keyword/index.html.twig', $this->context, $response);
	}

	/**
	 * The keyword view
	 *
	 * Display keyword articles
	 *
	 * @param Request $request The request instance
	 * @param integer $id The keyword id
	 * @param ?string $slug The keyword slug
	 * @return Response The rendered view
	 */
	public function view(Request $request, int $id, ?string $slug): Response {
		//Without keyword
		if (empty($this->context['keyword'] = $this->doctrine->getRepository(Keyword::class)->findByIdAsArray($id))) {
			//Throw 404
			throw $this->createNotFoundException($this->translator->trans('Unable to find keyword: %id%', ['%id%' => $id]));
		}

		//With invalid slug
		if ($slug !== $this->context['keyword']['slug']) {
			//Redirect on correctly spelled keyword
			return $this->redirectToRoute('rapsys_blog_keyword_view', ['id' => $this->context['keyword']['id'], 'slug' => $this->context['keyword']['slug']], Response::HTTP_MOVED_PERMANENTLY);
		}

		//Set modified
		$this->modified = $this->context['keyword']['modified'];

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
			$response->setEtag(md5(serialize($this->context['keyword'])));

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
			})($this->context['keyword']['articles'])
		);

		//Set title
		$this->context['title'] = $this->context['keyword']['title'];

		//Set description
		$this->context['description'] = $this->context['keyword']['description'];

		//Render the view
		return $this->render('@RapsysBlog/keyword/view.html.twig', $this->context, $response);
	}
}
