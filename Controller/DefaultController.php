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

/**
 * {@inheritdoc}
 */
class DefaultController extends AbstractController {
	/**
	 * The contact page
	 *
	 * @desc Send a contact mail to configured contact
	 *
	 * @param Request $request The request instance
	 * @return Response The rendered view or redirection
	 */
	public function contact(Request $request): Response {
		//Set title
		$this->context['title'] = $this->translator->trans('Contact');

		//Set description
		$this->context['description'] = $this->translator->trans('Welcome to raphaël\'s developer diary contact page');

		//Set keywords
		$this->context['keywords'] = $this->translator->trans('contact');

		//Set data
		$data = [];

		//With user
		if ($user = $this->getUser()) {
			//Set data
			$data = [
				'name' => $user->getRecipientName(),
				'mail' => $user->getMail()
			];
		}

		//Create the form according to the FormType created previously.
		//And give the proper parameters
		$form = $this->createForm('Rapsys\BlogBundle\Form\ContactType', $data, [
			'action' => $this->generateUrl('rapsys_blog_contact'),
			'method' => 'POST'
		]);

		if ($request->isMethod('POST')) {
			// Refill the fields in case the form is not valid.
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				//Get data
				$data = $form->getData();

				//Create message
				$message = (new TemplatedEmail())
					//Set sender
					->from(new Address($data['mail'], $data['name']))
					//Set recipient
					->to(new Address($this->config['contact']['mail'], $this->config['contact']['title']))
					//Set subject
					->subject($data['subject'])

					//Set path to twig templates
					->htmlTemplate('@RapsysBlog/mail/contact.html.twig')
					->textTemplate('@RapsysBlog/mail/contact.text.twig')

					//Set context
					->context(
						[
							'subject' => $data['subject'],
							'message' => strip_tags($data['message']),
						]+$this->context
					);

				//Try sending message
				//XXX: mail delivery may silently fail
				try {
					//Send message
					$this->mailer->send($message);

					//Redirect on the same route with sent=1 to cleanup form
					return $this->redirectToRoute($request->get('_route'), ['sent' => 1]+$request->get('_route_params'));
				//Catch obvious transport exception
				} catch(TransportExceptionInterface $e) {
					if ($message = $e->getMessage()) {
						//Add error message mail unreachable
						$form->get('mail')->addError(new FormError($this->translator->trans('Unable to contact: %mail%: %message%', ['%mail%' => $this->config['contact']['mail'], '%message%' => $this->translator->trans($message)])));
					} else {
						//Add error message mail unreachable
						$form->get('mail')->addError(new FormError($this->translator->trans('Unable to contact: %mail%', ['%mail%' => $this->config['contact']['mail']])));
					}
				}
			}
		}

		//Render template
		#return $this->render('@RapsysAir/form/contact.html.twig', ['form' => $form->createView(), 'sent' => $request->query->get('sent', 0)]+$this->context);
		return $this->render('@RapsysBlog/contact.html.twig', $this->context, $response);
	}

	/**
	 * The index page
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
		$this->context['title'] = $this->translator->trans('Home');

		//Set description
		$this->context['description'] = $this->translator->trans('Welcome to raphaël\'s developer diary');

		//Render the view
		return $this->render('@RapsysBlog/index.html.twig', $this->context, $response);
	}
}
