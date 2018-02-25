<?php

namespace Rapsys\BlogBundle\Controller;

use Rapsys\BlogBundle\Entity\Article;
use Rapsys\BlogBundle\Entity\Keyword;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PageController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller {
	/// Localized homepage
	//TODO: rewrite with canonical ?
	public function indexAction($_locale) {
		//Fetch articles list
		$articles = $this->getDoctrine()->getRepository(Article::class)->findArticles($_locale);

		//Set title
		$title = $this->get('translator')->trans($this->getParameter('blog.welcome'));

		//Render template
		return $this->render('RapsysBlogBundle::index.html.twig', array('title' => $title, 'articles' => $articles));
	}

	/// Legal informations
	public function aboutAction() {
		//Get translator
		$trans = $this->get('translator');

		//Set title
		$title = $trans->trans('About').' - '.$trans->trans($this->getParameter('blog.title'));

		//Render template
		return $this->render('RapsysBlogBundle::page/about.html.twig', array('title' => $title));
	}

	/// Contact form
	public function contactAction($_locale, \Symfony\Component\HttpFoundation\Request $request) {
		//Get translator
		$trans = $this->get('translator');

		//Set title
		$title = $trans->trans('Contact').' - '.$trans->trans($this->getParameter('blog.title'));

		//Create the form according to the FormType created previously.
		//And give the proper parameters
		$form = $this->createForm('Rapsys\BlogBundle\Form\ContactType', null, array(
			// To set the action use $this->generateUrl('route_identifier')
			'action' => $this->generateUrl('contact'),
			'method' => 'POST'
		));

		if ($request->isMethod('POST')) {
			// Refill the fields in case the form is not valid.
			$form->handleRequest($request);

			if ($form->isValid()) {
				$data = $form->getData();
				$message = \Swift_Message::newInstance()
					->setSubject($data['subject'])
					->setFrom(array($data['email'] => $data['name']))
					->setTo(array($this->getParameter('blog.contact_mail') => $this->getParameter('blog.contact_name')))
					->setBody($data['message'])
					->addPart(
						$this->renderView(
							'RapsysBlogBundle::mail/contact.html.twig',
							array(
								'blog_logo' => file_get_contents($this->getParameter('blog.logo'), false, null),
								'blog_title' => $this->getParameter('blog.title'),
								'blog_url' => $this->get('router')->generate('homepage', array('_locale' => $_locale), UrlGeneratorInterface::ABSOLUTE_URL),
								'subject' => $data['subject'],
								'contact_name' => $this->getParameter('blog.contact_name'),
								'message' => $data['message']
							)
						),
						'text/html'
					);
				//Send message
				if ($this->get('mailer')->send($message)) {
					//Redirect to cleanup the form
					return $this->redirectToRoute('contact', array('sent' => 1));
				}
			}
		}

		//Render template
		return $this->render('RapsysBlogBundle::page/contact.html.twig', array('title' => $title, 'form' => $form->createView(), 'sent' => $request->query->get('sent', 0)));
	}

	/// Article list
	public function articleIndexAction($_locale) {
		//Fetch articles list
		$articles = $this->getDoctrine()->getRepository(Article::class)->findArticles($_locale);

		//Get translator
		$trans = $this->get('translator');

		//Set title
		$title = $trans->trans('Articles list').' - '.$trans->trans($this->getParameter('blog.title'));

		//Render template
		return $this->render('RapsysBlogBundle::article/index.html.twig', array('title' => $title, 'articles' => $articles));
	}

	/// Article read
	public function articleReadAction($_locale, $_article) {
		//Protect article fetching
		try {
			$article = $this->getDoctrine()->getRepository(Article::class)->findArticle($_locale, $_article);
		//Catch no article case
		} catch (\Doctrine\ORM\NoResultException $e) {
			//Throw exception
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Not found!');
		}

		//Set title
		$title = $article['title'].' - '.$this->get('translator')->trans($this->getParameter('blog.title'));

		//Render template
		return $this->render('RapsysBlogBundle::article/read.html.twig', array('title' => $title, 'article' => $article));
	}

	/// Keyword list
	public function keywordIndexAction($_locale) {
		//Fetch keywords list
		$keywords = $this->getDoctrine()->getRepository(Keyword::class)->findKeywords($_locale);

		//Get translator
		$trans = $this->get('translator');

		//Set title
		$title = $trans->trans('Keywords list').' - '.$trans->trans($this->getParameter('blog.title'));

		//Render template
		return $this->render('RapsysBlogBundle::keyword/index.html.twig', array('title' => $title, 'keywords' => $keywords));
	}

	/// Keyword read
	function keywordReadAction($_locale, $_keyword) {
		//Protect keyword fetching
		try {
			$keyword = $this->getDoctrine()->getRepository(Keyword::class)->findKeyword($_locale, $_keyword);
		//Catch no keyword case
		} catch (\Doctrine\ORM\NoResultException $e) {
			//Throw exception
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Not found!');
		}

		//Set title
		$title = $keyword['title'].' - '.$this->get('translator')->trans($this->getParameter('blog.title'));

		//Render template
		return $this->render('RapsysBlogBundle::keyword/read.html.twig', array('title' => $title, 'keyword' => $keyword));
	}
}
