<?php

namespace Rapsys\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
	/**
	 * Redirect on first supported language version
	 */
	public function rootAction() {
		//Set default locale
		$locale = 'en';

		//Supported application languages
		$supportedLanguage = explode('|', $this->getParameter('blog.locales'));

		//Language list
		if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			//Init array
			$httpAcceptLanguage = [];

			//Extract languages
			foreach(explode(',',str_replace('-','_',$_SERVER['HTTP_ACCEPT_LANGUAGE'])) as $candidate) {
				//Extract candidate and optional weight
				@list($candidate, $weight) = explode(';', $candidate);
				if (!empty($candidate)) {
					$httpAcceptLanguage[!empty($weight)?$weight:1][] = $candidate;
				}
			}

			//Find first match
			if (!empty($httpAcceptLanguage)) {
				foreach($httpAcceptLanguage as $weight => $candidates) {
					if (($candidate = array_intersect($candidates, $supportedLanguage)) && ($candidate = reset($candidate))) {
						$locale = $candidate;
						break;
					}
				}
			}
		}

		//Redirect to localised homepage
		return $this->redirectToRoute('homepage', array('_locale' => $locale), 302);
	}
}
