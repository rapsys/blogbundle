<?php

namespace Rapsys\BlogBundle\DataFixtures\ORM;

#Article.php  ArticleTranslation.php  Author.php  Keyword.php  KeywordTranslation.php  Language.php  Site.php  SiteTranslation.php

class Fixtures extends \Doctrine\Bundle\FixturesBundle\Fixture {
	public function load(\Doctrine\Common\Persistence\ObjectManager $manager) {
		//Convert all title to safe slug
		function slugify($str) {
			return preg_replace('/[\/_|+ -]+/', '-', strtolower(trim(preg_replace('/[^a-zA-Z0-9\/_|+ -]/', '', str_replace(array('\'', '"'), ' ', iconv('UTF-8', 'ASCII//TRANSLIT', $str))), '-')));
		}
		//Language tree
		$langTree = array(
			'fr' => array(
				'iso6391' => 'fr',
				'iso6393' => 'fra',
				'locales' => array(
					'fr' => 'Français',
					'en' => 'French'
				)
			),
			'en' => array(
				'iso6391' => 'en',
				'iso6393' => 'eng',
				'locales' => array(
					'en' => 'English',
					'fr' => 'Anglais'
				)
			)
		);

		//Create languages
		$languages = array();
		foreach($langTree as $langs) {
			$language = new \Rapsys\BlogBundle\Entity\Language();
			$language->setIso6391($langs['iso6391']);
			$language->setIso6393($langs['iso6393']);
			$language->setCreated(new \DateTime('now'));
			$language->setUpdated(new \DateTime('now'));
			$manager->persist($language);
			$languages[$langs['iso6391']] = $language;
			unset($language);
		}

		//Flush to get the ids
		$manager->flush();

		//Create language translations
		foreach($langTree as $target => $langs) {
			foreach($langs['locales'] as $lang => $title) {
				$languageTranslation = new \Rapsys\BlogBundle\Entity\LanguageTranslation();
				$languageTranslation->setTitle($title);
				$languageTranslation->setLanguage($languages[$lang]);
				$languageTranslation->setLanguageId($languages[$lang]->getId());
				$languageTranslation->setTarget($languages[$target]);
				$languageTranslation->setTargetId($languages[$target]->getId());
				$languageTranslation->setCreated(new \DateTime('now'));
				$languageTranslation->setUpdated(new \DateTime('now'));
				$manager->persist($languageTranslation);
				$manager->flush();
				unset($languageTranslation);
			}
		}

		//Create 3 sites
		$sites = array();
		foreach(array('blog.rapsys.eu') as $domain) {
			$site = new \Rapsys\BlogBundle\Entity\Site();
			$site->setDomain($domain);
			$site->setCreated(new \DateTime('now'));
			$site->setUpdated(new \DateTime('now'));
			$manager->persist($site);
			$sites[] = $site;
			unset($site);
		}

		//Author tree
		$authorTree = array(
			/*'Ernest Hemingway' => array(
				'fr' => 'Né le 21 juillet 1899 à Oak Park dans l\'Illinois aux États-Unis, mort le 2 juillet 1961 à Ketchum, fût un écrivain, journaliste et correspondant de guerre américain.',
				'en' => 'Born on July 21, 1899 in Oak Park, Illinois, deceased on July 2, 1961 in Ketchum, was an American novelist, short story writer and journalist.'
			),
			'William Shakespeare' => array(
				'fr' => 'Baptisé le 26 avril 1564 à Stratford-upon-Avon et mort le 23 avril 1616 dans la même ville, est considéré comme l\'un des plus grands poètes, dramaturges et écrivains de la culture anglaise.',
				'en' => 'Baptised on April 26, 1564, deceased on April 23, 1616, was an English poet, playwright and actor, widely regarded as the greatest writer in the English language and the world\'s pre-eminent dramatist.'
			),
			'George Orwell' => array(
				'fr' => 'Eric Arthur Blair, né le 25 juin 1903 à Motihari pendant la période du Raj britannique et mort le 21 janvier 1950 à Londres, plus connu sous son nom de plume, est un écrivain et journaliste anglais.',
				'en' => 'Eric Arthur Blair, born on June 25, 1903, deceased on January 21, 1950, better known by his pen name, was an English novelist, essayist, journalist, and critic.'
			)*/
			'raphaël g.' => array(
				'fr' => 'Raphaël G., né en 1984, est développeur web depuis 2007. Passionné par le monde du logiciel libre, depuis 2004, où il commence à contribuer à une distribution linux, connue maintenant sous le nom Mageia, un certain chemin a été parcouru dès lors.',
				'en' => 'Raphaël G., born in 1984, is a web developper since 2007. Interested in free software, since 2004, when he begin contributing to a linux distribution, known now as Mageia, some path has been traveled since then.'
			)
		);

		//Create 3 authors
		$authors = array();
		foreach($authorTree as $name => $data) {
			$author = new \Rapsys\BlogBundle\Entity\Author();
			$author->setName($name);
			$author->setSlug(slugify($name));
			$author->setCreated(new \DateTime('now'));
			$author->setUpdated(new \DateTime('now'));
			$manager->persist($author);
			//Flush to get the id
			$manager->flush();
			$authors[] = $author;
			foreach($data as $lang => $description) {
				$authorTranslation = new \Rapsys\BlogBundle\Entity\AuthorTranslation();
				$authorTranslation->setDescription($description);
				$authorTranslation->setAuthor($author);
				$authorTranslation->setAuthorId($author->getId());
				$authorTranslation->setLanguage($languages[$lang]);
				$authorTranslation->setLanguageId($languages[$lang]->getId());
				$authorTranslation->setCreated(new \DateTime('now'));
				$authorTranslation->setUpdated(new \DateTime('now'));
				$manager->persist($authorTranslation);
			}
			unset($author);
		}

		//Keyword tree
		$keywordTree = array(
			'png' => array(
				'fr' => array(
					'title' => 'PNG',
					'description' => 'Le Portable Network Graphics (PNG) est un format ouvert d’images numériques, qui a été créé pour remplacer le format GIF, à l’époque propriétaire et dont la compression était soumise à un brevet'
				),
				'en' => array(
					'title' => 'PNG',
					'description' => 'Portable Network Graphics (PNG) is an raster graphics file open format that supports lossless data compression'
				)
			),
			'imagick' => array(
				'fr' => array(
					'title' => 'Imagick',
					'description' => 'Image Magick est une collection de logiciels libres pour afficher, convertir et modifier des images numériques matricielles ou vectorielles dans de nombreux formats'
				),
				'en' => array(
					'title' => 'Imagick',
					'description' => 'ImageMagick is a free and open-source software suite for displaying, converting, and editing raster image and vector image files'
				)
			),
			'image' => array(
				'fr' => array(
					'title' => 'Image',
					'description' => 'Une image est une représentation visuelle, voire mentale, de quelque chose'
				),
				'en' => array(
					'title' => 'Image',
					'description' => 'An image is an artifact that depicts visual perception, for example, a photo or a two-dimensional picture, that has a similar appearance to some subject'
				)
			),
			'varnish' => array(
				'fr' => array(
					'title' => 'Varnish',
					'description' => 'Varnish est un serveur de cache HTTP déployé en tant que proxy inverse entre les serveurs d\'application et les clients'
				),
				'en' => array(
					'title' => 'Varnish',
					'description' => 'Varnish is an HTTP cache server deployed as a reverse proxy between applications servers and clients'
				)
			),
			'webservice' => array(
				'fr' => array(
					'title' => 'Service web',
					'description' => 'Un service web, ou service de la toile, est un protocole d\'interface informatique de la famille des technologies web permettant la communication et l\'échange de données entre applications et systèmes hétérogènes'
				),
				'en' => array(
					'title' => 'Web service',
					'description' => 'A web service is a service offered by an electronic device to another electronic device, communicating with each other via the World Wide Web'
				)
			),
			'rest' => array(
				'fr' => array(
					'title' => 'REST',
					'description' => 'Representational state transfer (REST) ou services web RESTful est une manière de fournir de l\'intéropérabilité entre systèmes d\'information sur Internet'
				),
				'en' => array(
					'title' => 'REST',
					'description' => 'Representational state transfer (REST) or RESTful web services are a way of providing interoperability between computer systems on the Internet'
				)
			),
			'hateoas' => array(
				'fr' => array(
					'title' => 'HATEOAS',
					'description' => 'HATEOAS, abréviation d\'Hypermedia As Engine of Application State, Hypermédia en tant que moteur de l\'état d\'application, constitue une contrainte de l\'architecture d\'application REST qui la distingue de la plupart des autres architectures d\'applications réseau'
				),
				'en' => array(
					'title' => 'HATEOAS',
					'description' => 'HATEOAS, abbreviation of Hypermedia As The Engine Of Application State, is a constraint of the REST application architecture that distinguishes it from other network application architectures'
				)
			),
			'uri' => array(
				'fr' => array(
					'title' => 'URI',
					'description' => 'En technologie de l\'information, une URI, abréviation d\'Uniform Resource Identifier, Identifiant uniforme de ressource, est une chaine de caractères utilisée pour identifier une ressource'
				),
				'en' => array(
					'title' => 'URI',
					'description' => 'In information technology, a Uniform Resource Identifier (URI) is a string of characters used to identify a resource'
				)
			),
			'cidr' => array(
				'fr' => array(
					'title' => 'CIDR',
					'description' => 'Routage inter-domaine sans classe, de l\'anglais Classless Inter-Domain Routing, CIDR, est une méthode pour agréger des adresses IP et les router'
				),
				'en' => array(
					'title' => 'CIDR',
					'description' => 'Classless Inter-Domain Routing, CIDR, is a method for aggregating IP addresses and route them'
				)
			),
			'amazon' => array(
				'fr' => array(
					'title' => 'Amazon',
					'description' => 'Amazon Elastic Compute Cloud ou EC2 est un service proposé par Amazon permettant à des tiers de louer des serveurs sur lesquels exécuter leurs propres applications web'
				),
				'en' => array(
					'title' => 'Amazon',
					'description' => 'Amazon Elastic Compute Cloud or EC2 is an Amazon server renting service allowing third party to run their own web application'
				)
			),
			'php' => array(
				'fr' => array(
					'title' => 'PHP',
					'description' => 'PHP : Hypertext Preprocessor, plus connu sous son sigle PHP, est un langage de programmation libre, principalement utilisé pour produire des pages Web dynamiques via un serveur HTTP'
				),
				'en' => array(
					'title' => 'PHP',
					'description' => 'PHP: Hypertext Preprocessor, better known as PHP, is an open programming language, used mostly to produce dynamic web pages through an HTTP server'
				)
			),
			'mysql' => array(
				'fr' => array(
					'title' => 'MySQL',
					'description' => 'MySQL est un système de gestion de bases de données relationnelles libre'
				),
				'en' => array(
					'title' => 'MySQL',
					'description' => 'MySQL is an open-source relational database management system, RDBMS'
				)
			),
			'azure' => array(
				'fr' => array(
					'title' => 'Azure',
					'description' => 'Microsoft Azure, anciennement Windows Azure, est une plateforme applicative en nuage crée par Microsoft pour construire, tester, déployer et gérer des applications et services sur un réseau global de centres de données opéré par Microsoft'
				),
				'en' => array(
					'title' => 'Azure',
					'description' => 'Microsoft Azure, formerly Windows Azure, is a cloud computing service created by Microsoft for building, testing, deploying, and managing applications and services through a global network of Microsoft-managed data centers'
				)
			),
			'microsoft' => array(
				'fr' => array(
					'title' => 'Microsoft',
					'description' => 'Microsoft Corporation est une multinationale informatique et micro-informatique américaine, fondée en 1975 par Bill Gates et Paul Allen'
				),
				'en' => array(
					'title' => 'Microsoft',
					'description' => 'Microsoft Corporation is an american multinational technology company, founded in 1975 by Bill Gates and Paul Allen'
				)
			)
		);

		//Create 3 keywords
		$keywords = array();
		foreach($keywordTree as $name => $data) {
			$keyword = new \Rapsys\BlogBundle\Entity\Keyword();
			$keyword->setCreated(new \DateTime('now'));
			$keyword->setUpdated(new \DateTime('now'));
			$manager->persist($keyword);
			//Flush to get the id
			$manager->flush();
			$keywords[$name] = $keyword;
			foreach($data as $lang => $datas) {
				$keywordTranslation = new \Rapsys\BlogBundle\Entity\KeywordTranslation();
				$keywordTranslation->setTitle($datas['title']);
				$keywordTranslation->setSlug(slugify($datas['title']));
				$keywordTranslation->setDescription($datas['description']);
				$keywordTranslation->setKeyword($keyword);
				$keywordTranslation->setKeywordId($keyword->getId());
				$keywordTranslation->setLanguage($languages[$lang]);
				$keywordTranslation->setLanguageId($languages[$lang]->getId());
				$keywordTranslation->setCreated(new \DateTime('now'));
				$keywordTranslation->setUpdated(new \DateTime('now'));
				$manager->persist($keywordTranslation);
			}
			unset($keyword);
		}

		//Article tree
		$articleTree = array(
			array(
				'keywords' => array('image', 'imagick', 'png'),
				'fr' => array(
					'title' => 'Comment détecter la tranparence dans des images PNG en PHP de manière fiable',
					'description' => 'J\'ai récemment du trouver comment détecter en PHP les images PNG transparentes.
Les codes trouvés ne semblaient pas fonctionner de manière satisfaisante pour les différents types de PNG à contrôler.
Voici la fonction que j\'ai fini par utiliser.',
					'body' => 'J\'ai récemment du trouver comment détecter en PHP les images PNG transparentes.
Les codes trouvés ne semblaient pas fonctionner de manière satisfaisante pour les différents types de PNG à contrôler.
J\'ai fini par utiliser la fonction suivante:
[code=php]
function png_has_transparency($im) {
        //Retrieve content from imagick object
        $content = $im->getImageBlob();

        //Detect 32bit png (each pixel has tranparency level)
        if (ord(substr($content, 25, 1)) & 4) {
                //Fetch iterator
                $p = $im->getPixelIterator();

                //Loop on each row
                foreach($p as $r) {
                        //Loop on each row pixel
                        foreach($r as $pix) {
                                //Check if pixel has partial transparency
                                if ($pix->getColorValue(Imagick::COLOR_ALPHA) != 1) {
                                        return true;
                                }
                        }
                }
        //Check 8bit png transparency
        } elseif (stripos($content, \'PLTE\') !== false || stripos($content, \'tRNS\') !== false) {
                return true;
        }

        //Didn\'t found clue of transparency
        return false;
}
[/code]

Cette fonction fonctionne avec les deux seules possibilités : PNG 8 et 32 bits.

Le premier cas est un PNG 32 bits avec transparence activée, on doit alors vérifier l\'opacité de chaque pixel savoir si l\'image a de la transparence ou non.

Le second cas est un PNG 8 bits, on a simplement à détecter un marqueur de transparence dans le contenu du fichier.

Dans cette configuration de fonction, on lit seulement une partie du PNG 32 bits jusqu\'à détection d\'un pixel transparent où on analyse le contenu jusqu\'à trouver un marqueur de transparence dans un PNG 8 bits.

Les pires cas seront un PNG 32 bits avec marqueur de transparence sans pixel transparent ou PNG 8 bits sans marqueur de transparence.

Selon les probabilités de rencontrer les différents cas de transparence vous pouvez être intéressé pour renverser l\'ordre des tests de cette fonction.

Un grand merci à ces articles qui expliquent plus en détail comment fonctionnent les différentes parties de ce code:
[ul]
[li][url]https://www.jonefox.com/blog/2011/04/15/how-to-detect-transparency-in-png-images[/url][/li]
[li][url]http://camendesign.com/code/uth1_is-png-32bit[/url][/li]
[li][url]https://stackoverflow.com/questions/5495275/how-to-check-if-an-image-has-transparency-using-gd[/url][/li]
[/ul]

En espérant que cela puisse aider quelques personnes.'
				),
				'en' => array(
					'title' => 'How to reliably detect PNG image transparency with PHP',
					'description' => 'I recently had to find out if a PNG has transparency using PHP.
All the code I found didn\'t seemed to work correctly for the various kind of PNG I had to deal with.
Here is the function I used.',
					'body' => 'I recently had to find out if a PNG has transparency using PHP.
All the code I found didn\'t seemed to work correctly for the various kind of PNG I had to deal with.

I finished using the following function:
[code=php]
function png_has_transparency($im) {
        //Retrieve content from imagick object
        $content = $im->getImageBlob();

        //Detect 32-bit png (each pixel has tranparency level)
        if (ord(substr($content, 25, 1)) & 4) {
                //Fetch iterator
                $p = $im->getPixelIterator();

                //Loop on each row
                foreach($p as $r) {
                        //Loop on each row pixel
                        foreach($r as $pix) {
                                //Check if pixel has partial transparency
                                if ($pix->getColorValue(Imagick::COLOR_ALPHA) != 1) {
                                        return true;
                                }
                        }
                }
        //Check 8-bit png transparency
        } elseif (stripos($content, \'PLTE\') !== false || stripos($content, \'tRNS\') !== false) {
                return true;
        }

        //Didn\'t found clue of transparency
        return false;
}
[/code]

This function works with the only two transparency possibilities: 8 and 32-bit PNG.

The first case is a 32-bit PNG with transparency enabled, we have then to check every pixel to detect if it has transparent part or not.

The second case is a 8-bit PNG, then we only have to look the file content for transparency markers.

In this function configuration, we only read part of the file in 32-bit PNG until we detect one transparent pixel or parse content until transparency marker is detected in 8-bit PNG.

The worst case scenario will be 32-bit PNG with transparency flag without transparency or 8-bit PNG without transparency flag.

Depending on how likely you are to have transparency in each cases you might want to reverse the flow of this function.

Big thanks to these articles which expains how these parts work in a bit more detail:
[ul]
[li][url]https://www.jonefox.com/blog/2011/04/15/how-to-detect-transparency-in-png-images[/url][/li]
[li][url]http://camendesign.com/code/uth1_is-png-32bit[/url][/li]
[li][url]https://stackoverflow.com/questions/5495275/how-to-check-if-an-image-has-transparency-using-gd[/url][/li]
[/ul]

Hope this helps someone else out there.'
				)
			),
			array(
				'keywords' => array('hateoas', 'rest', 'uri', 'varnish', 'webservice'),
				'fr' => array(
					'title' => 'Mise en cache de webservice avec varnish',
					'description' => 'J\'ai eu récemment à trouver comment mettre en cache les réponses d\'un webservice.
Voici la configuration varnish qui a répondu à mes besoins.',
					'body' => 'J\'ai eu récemment à trouver comment mettre en cache les réponses d\'un webservice.

L\'API RESTfull du webservice sert de passerelle entre un API privé HATEOAS et un client générant plus de 500 000 requêtes par jour.

La première surprise est qu\'un client bien élevé, envoyant un en-tête Authorization: Bearer, ne sera pas mis en cache par Varnish par défaut !

Forçons le fonctionnement standard avec l\'en-tête pour le préfixe de l\'uri de notre webservice:
[code=varnish]
sub vcl_recv {
	# Force la mise en cache de la réponse même avec req.http.Authorization présent
	if (req.http.Authorization) {
		if (req.url ~ "^/webservice/uri/prefix/") {
			return (lookup);
		}
	}
}
[/code]

Ce changement a des conséquences sur la sécurité, puisque n\'importe quelle personne autorisée à interroger Varnish sera en mesure de récupérer un résultat en cache sans s\'identifier.

Il est important de valider la valeur de l\'en-tête Authorization avant de fournir le résultat depuis le cache.

Notre webservice a trois réponses possibles :
[ul]
[li]200: les données en JSON[/li]
[li]404: données non trouvées[/li]
[li]410: données plus jamais disponibles[/li]
[/ul]

Mettons en cache les résultats selon le code de retour :
[code=varnish]
sub vcl_fetch {
       if (req.url ~ "^/webservice/uri/prefix/") {
		if (beresp.status == 404) {
			set beresp.ttl = 7d;
		}
		if (beresp.status == 410) {
			set beresp.ttl = 7d;
		}
		if (beresp.status == 200) {
			set beresp.ttl = 24h;
		}
	}
}
[/code]

Avec cette configuration, on a divisé par 5 la quantité de demandes sur notre passerelle pour le client qui n\'était pas en mesure de mettre en cache lui-même nos résultats.'
				),
				'en' => array(
					'title' => 'Caching webservice with varnish',
					'description' => 'I recently had to find a way to cache a webservice anwsers.
Here is the Varnish configuration fitting my needs.',
					'body' => 'I recently had to find a way to cache a webservice anwsers.

The webservice is a RESTfull API serving as a gateway between a private HATEOAS API and a client generating more than 500 000 requests a day.

The first surprise is that if your well educated client, sending you a header Authorization: Bearer, will not be cached by default by Varnish !

Let\'s force back the standard behaviour with this header for our webservice uri prefix:
[code=varnish]
sub vcl_recv {
	# Force cache response even with req.http.Authorization set
	if (req.http.Authorization) {
		if (req.url ~ "^/webservice/uri/prefix/") {
			return (lookup);
		}
	}
}
[/code]

This has security implication, because anyone allowed to request varnish will be able to retrieve a cached result without authentification.

It is important to validate the Authorization header value before serving the result from cache.

Now, our webservice has three possibles answers :
[ul]
[li]200: the data in JSON[/li]
[li]404: data was not found[/li]
[li]410: data is not available anymore[/li]
[/ul]

Let\'s cache our results depending on the reponse code:
[code=varnish]
sub vcl_fetch {
       if (req.url ~ "^/webservice/uri/prefix/") {
		if (beresp.status == 404) {
			set beresp.ttl = 7d;
		}
		if (beresp.status == 410) {
			set beresp.ttl = 7d;
		}
		if (beresp.status == 200) {
			set beresp.ttl = 24h;
		}
	}
}
[/code]

With this configuration, we divided by 5 the quantity of request on our gateway from the client who was not able to cache our result himself.'
				)
			),
			array(
				'keywords' => array('amazon', 'azure', 'cidr', 'microsoft', 'mysql', 'php', 'webservice'),
				'fr' => array(
					'title' => 'Gestion des plages d\'IP en PHP/MySQL',
					'description' => 'J\'ai eu récemment à trouver comment restreindre l\'accès à un service en ligne à certaines plages d\'IP. Voici la solution qui a répondu à mes besoins.',
					'body' => 'J\'ai récemment du autoriser l\'accès à un service en ligne à seulement quelques plages d\'IP.

Premièrement, voyons comment calculer la première et la dernière adresse IP d\'une plage (bloc CIDR) avec sa base et son masque :
[code=php]
$range = array(\'127.0.0.1\', 8);
function rangeBegin($range) {
	return $range[0];
}
function rangeEnd($range) {
	return long2ip(ip2long($range[0]) | ((1 << (32 - $range[1])) - 1));
}
[/code]

Maintenant comment vérifier si une IP est présente dans une plage (bloc CIDR) :
[code=php]
$ip = \'127.0.0.1\';
$range = array(\'127.0.0.1\', 8);
function ipInRange($ip, $range) {
	if (ip2long($range[0]) <= ip2long($ip) && ip2long($ip) <= (ip2long($range[0]) | ((1 << (32 - $range[1])) - 1))) {
		return true;
	}
	return false;
}
[/code]

En premier bonus, comment récupérer les plages d\'IP d\'amazon :
[code=php]
function fetchAmazonRange() {
	//Init array
	$amazonRanges = array();

	$ctx = stream_context_create(
		array(
			\'http\' => array(
				\'method\' => \'GET\',
				\'max_redirects\' => 0,
				\'timeout\' => 5,
				\'ignore_errors\' => false,
				\'header\' => array(
					\'Connection: close\',
					\'Accept: application/json\'
				)
			)
		)
	);

	//Fetch json
	if (($json = file_get_contents(\'https://ip-ranges.amazonaws.com/ip-ranges.json\', false, $ctx)) === false) {
		return null;
	}

	//Decode it
	if (($json = json_decode($json)) === null || empty($json->prefixes)) {
		return false;
	}

	//Deal with prefixes
	foreach($json->prefixes as $range) {
		//Skip ipv6 and invalid ranges
		if (empty($range->ip_prefix)||!preg_match(\'/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)\/([0-9]+)$/\', $range->ip_prefix, $matche
s)) {
			continue;
		}
		//Remove whole match
		array_shift($matches);
		//Add ip and mask
		$amazonRanges[] = $matches;
	}

	//Send back result
	return $amazonRanges;
}
[/code]

Urls pour les plages d\'IP de Microsoft Azure :
[ul]
[li][url]https://www.microsoft.com/en-us/download/details.aspx?id=41653[/url][/li]
[li][url]https://msdn.microsoft.com/library/mt757330.aspx[/url][/li]
[/ul]'
				),
				'en' => array(
					'title' => 'Dealing with IP range in PHP/MySQL',
					'description' => 'I recently had to deal with CIDR blocks to tighten some webservice security.
Here is how I designed it to fulfill my needs.',
					'body' => 'I recently had to deal with CIDR blocks to tighten some webservice security.

First let\'s see how to compute the first and last address of an IP range with just the block base IP and mask:
[code=php]
$range = array(\'127.0.0.1\', 8);
function rangeBegin($range) {
	return $range[0];
}
function rangeEnd($range) {
	return long2ip(ip2long($range[0]) | ((1 << (32 - $range[1])) - 1));
}
[/code]

How to detect if an IP is present in a CIDR block:
[code=php]
$ip = \'127.0.0.1\';
$range = array(\'127.0.0.1\', 8);
function ipInRange($ip, $range) {
	if (ip2long($range[0]) <= ip2long($ip) && ip2long($ip) <= (ip2long($range[0]) | ((1 << (32 - $range[1])) - 1))) {
		return true;
	}
	return false;
}
[/code]

As a first bonus, how to retrieve amazon IP ranges:
[code=php]
function fetchAmazonRange() {
	//Init array
	$amazonRanges = array();

	$ctx = stream_context_create(
		array(
			\'http\' => array(
				\'method\' => \'GET\',
				\'max_redirects\' => 0,
				\'timeout\' => 5,
				\'ignore_errors\' => false,
				\'header\' => array(
					\'Connection: close\',
					\'Accept: application/json\'
				)
			)
		)
	);

	//Fetch json
	if (($json = file_get_contents(\'https://ip-ranges.amazonaws.com/ip-ranges.json\', false, $ctx)) === false) {
		return null;
	}

	//Decode it
	if (($json = json_decode($json)) === null || empty($json->prefixes)) {
		return false;
	}

	//Deal with prefixes
	foreach($json->prefixes as $range) {
		//Skip ipv6 and invalid ranges
		if (empty($range->ip_prefix)||!preg_match(\'/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)\/([0-9]+)$/\', $range->ip_prefix, $matche
s)) {
			continue;
		}
		//Remove whole match
		array_shift($matches);
		//Add ip and mask
		$amazonRanges[] = $matches;
	}

	//Send back result
	return $amazonRanges;
}
[/code]

Microsoft Azure Ip ranges urls:
[ul]
[li][url]https://www.microsoft.com/en-us/download/details.aspx?id=41653[/url][/li]
[li][url]https://msdn.microsoft.com/library/mt757330.aspx[/url][/li]
[/ul]'
				)
			),
		);

		//Create 3 articles
		foreach($articleTree as $i => $data) {
			$article = new \Rapsys\BlogBundle\Entity\Article();
			$article->setCreated(new \DateTime('now'));
			$article->setUpdated(new \DateTime('now'));
			foreach($data['keywords'] as $keyword) {
				$article->addKeyword($keywords[$keyword]);
			}
			unset($data['keywords']);
			$article->setSite($sites[0]);
			$article->setAuthor($authors[0]);
			$manager->persist($article);
			//Flush to get the id
			$manager->flush();
			$articles[] = $article;
			foreach($data as $lang => $datas) {
				$articleTranslation = new \Rapsys\BlogBundle\Entity\ArticleTranslation();
				$articleTranslation->setTitle($datas['title']);
				$articleTranslation->setSlug(slugify($datas['title']));
				$articleTranslation->setDescription($datas['description']);
				$articleTranslation->setBody($datas['body']);
				$articleTranslation->setArticle($article);
				$articleTranslation->setArticleId($article->getId());
				$articleTranslation->setLanguage($languages[$lang]);
				$articleTranslation->setLanguageId($languages[$lang]->getId());
				$articleTranslation->setCreated(new \DateTime('now'));
				$articleTranslation->setUpdated(new \DateTime('now'));
				$manager->persist($articleTranslation);
			}
			unset($article);
		}

		$manager->flush();
	}
}

//TODO:
//- add disqus article
//- add code for ip range detection
//- add code for amazon ip range detection
//- add code for microsoft ip range detection
//- add ??? article*/
//
//- add article ihttpd
//- add article distgen
//- add article distcook
//- add article install
//
//while [ ! -z "$(pidof dd)" ]; do killall -USR1 dd; sleep 300; done; echo 'Server ready' | mail -r 'aurae@rapsys.eu' -s 'Server ready' aurae@rapsys.eu


/*
Pour l'article sur distgen, un coup d'explication sur le perl

#! /usr/bin/perl

use strict;
use warnings;

use Socket qw(AF_INET inet_ntop inet_pton);

my ($ip, $mask) = @ARGV;

# binmask
#print (2**$mask-1)<<(32-$mask), "\n";

# first ip
#print Socket::inet_ntop(Socket::AF_INET, pack('N', unpack('N', Socket::inet_pton(Socket::AF_INET, $ip)) & (2**$mask-1)<<(32-$mask))), "\n";

# doted mask
#print join('.', unpack('C4', pack('N', (2**$mask-1)<<(32-$mask)))), "\n";

# last ip
#print Socket::inet_ntop(Socket::AF_INET, pack('N', unpack('N', Socket::inet_pton(Socket::AF_INET, $ip)) | (2**(32 - $mask)))), "\n";

 */
