<?php

namespace Rapsys\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller {
	/**
	 * Localized homepage
	 */
	public function indexAction() {
		return $this->render('RapsysBlogBundle::index.html.twig');
	}

	/**
	 * Legal informations
	 */
	public function aboutAction() {
		return $this->render('RapsysBlogBundle::about.html.twig');
	}

	/**
	 * Contact form
	 */
	public function contactAction() {
		return $this->render('RapsysBlogBundle::contact.html.twig');
	}
}
