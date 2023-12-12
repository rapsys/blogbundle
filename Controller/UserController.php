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
use Rapsys\BlogBundle\Entity\User;

/**
 * {@inheritdoc}
 */
class UserController extends AbstractController {
	/**
	 * The user index
	 *
	 * Display users
	 *
	 * @param Request $request The request instance
	 * @return Response The rendered view
	 */
	public function index(Request $request): Response {
		//With not enough users
		if (($this->count = $this->doctrine->getRepository(User::class)->findCountAsInt()) < $this->page * $this->limit) {
			//Throw 404
			throw $this->createNotFoundException($this->translator->trans('Unable to find users'));
		}

		//Get users
		if ($this->context['users'] = $this->doctrine->getRepository(User::class)->findAllAsArray($this->page, $this->limit)) {
			//Set modified
			$this->modified = max(array_map(function ($v) { return $v['modified']; }, $this->context['users']));
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
			$response->setEtag(md5(serialize($this->context['users'])));

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

				//Iterate on users
				foreach($t as $u) {
					//Non empty articles
					if (!empty($u['articles'])) {
						//Iterate on articles
						foreach($u['articles'] as $a) {
							//Non empty keywords
							if (!empty($a['keywords'])) {
								//Iterate on keywords
								foreach($a['keywords'] as $k) {
									//Set keyword
									$r[$k['title']] = $k['title'];
								}
							}
						}
					}
				}

				//Sort array
				sort($r);

				//Return array
				return $r;
			})($this->context['users'])
		);

		//Set title
		$this->context['title'] = $this->translator->trans('Users list');

		//Set description
		$this->context['description'] = $this->translator->trans('Welcome to raphaël\'s developer diary user listing');

		//Render the view
		return $this->render('@RapsysBlog/user/index.html.twig', $this->context, $response);
	}

	/**
	 * The user view
	 *
	 * Display user, articles and keywords
	 *
	 * @param Request $request The request instance
	 * @param integer $id The user id
	 * @param ?string $slug The user slug
	 * @return Response The rendered view
	 */
	public function view(Request $request, int $id, ?string $slug): Response {
		//Without user
		if (empty($this->context['user'] = $this->doctrine->getRepository(User::class)->findByIdAsArray($id))) {
			//Throw 404
			throw $this->createNotFoundException($this->translator->trans('Unable to find user: %id%', ['%id%' => $id]));
		}

		//With invalid slug
		if ($slug !== $this->context['user']['slug']) {
			//Redirect on correctly spelled user
			return $this->redirectToRoute('rapsys_blog_user_view', ['id' => $this->context['user']['id'], 'slug' => $this->context['user']['slug']], Response::HTTP_MOVED_PERMANENTLY);
		}

		//Set modified
		$this->modified = $this->context['user']['modified'];

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
			$response->setEtag(md5(serialize($this->context['user'])));

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
			(function ($u) {
				//Return array
				$r = [];

				//Non empty articles
				if (!empty($u['articles'])) {
					//Iterate on articles
					foreach($u['articles'] as $a) {
						//Non empty keywords
						if (!empty($a['keywords'])) {
							//Iterate on keywords
							foreach($a['keywords'] as $k) {
								//Set keyword
								$r[$k['title']] = $k['title'];
							}
						}
					}
				}

				//Sort array
				sort($r);

				//Return array
				return $r;
			})($this->context['user'])
		);

		//Set title
		$this->context['title'] = $this->context['user']['pseudonym'];

		//Set description
		//TODO: Add user creation ? Add a description field ?
		#$this->context['description'] = $this->context['user']['description'];

		//Render the view
		return $this->render('@RapsysBlog/user/view.html.twig', $this->context, $response);
	}
}
