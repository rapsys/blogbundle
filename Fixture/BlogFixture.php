<?php declare(strict_types=1);

/*
 * This file is part of the Rapsys BlogBundle package.
 *
 * (c) Raphaël Gertz <symfony@rapsys.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rapsys\BlogBundle\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Rapsys\PackBundle\Util\SluggerUtil;

use Rapsys\BlogBundle\Entity\Civility;
use Rapsys\BlogBundle\Entity\Group;
use Rapsys\BlogBundle\Entity\User;
use Rapsys\BlogBundle\Entity\UserTranslation;
use Rapsys\BlogBundle\Entity\Keyword;
use Rapsys\BlogBundle\Entity\KeywordTranslation;
use Rapsys\BlogBundle\Entity\Article;
use Rapsys\BlogBundle\Entity\ArticleTranslation;

/**
 * {@inheritDoc}
 */
class BlogFixture extends Fixture {
	/**
	 * @var Rapsys\PackBundle\Util\SluggerUtil
	 */
	private SluggerUtil $slugger;

	/**
	 * Constructor
	 */
	public function __construct(SluggerUtil $slugger) {
		//Set slugger
		$this->slugger = $slugger;
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager) {
		//Civility tree
		$civilityTree = [
			'Mister',
			'Madam',
			'Miss'
		];

		//Create titles
		$civilitys = [];
		foreach($civilityTree as $civilityData) {
			$civility = new Civility($civilityData);
			$manager->persist($civility);
			$civilitys[$civilityData] = $civility;
			unset($civility);
		}

		//Group tree
		//XXX: ROLE_XXX is required by
		$groupTree = [
			'User',
			'Admin'
		];

		//Create groups
		$groups = [];
		foreach($groupTree as $groupData) {
			$group = new Group($groupData);
			$manager->persist($group);
			$groups[$groupData] = $group;
			unset($group);
		}

		//Flush to get the ids
		$manager->flush();

		//User tree
		$userTree = [
			[
				'civility' => 'Mister',
				'group' => 'Admin',
				'mail' => 'blog@rapsys.eu',
				'password' => 'test',
				'forename' => 'Raphaël',
				'surname' => 'Gertz',
				'active' => true,
				'enable' => false,
				'pseudonym' => 'Rapsys',
				'slug' => $this->slugger->slug('Raphaël Gertz (rapsys)'),
				'translations' => [
					'en_gb' => 'Raphaël Gertz, born in 1984, is a web developper since 2007. Interested in free software, since 2004, when he begin contributing to a linux distribution, known now as Mageia, some path has been traveled since then.',
					'fr_fr' => 'Raphaël Gertz, né en 1984, est développeur web depuis 2007. Passionné par le monde du logiciel libre, depuis 2004, où il commence à contribuer à une distribution linux, connue maintenant sous le nom Mageia, un certain chemin a été parcouru dès lors.'
				]
			],
		];

		//Create users
		$users = [];
		foreach($userTree as $userData) {
			$user = new User($userData['mail'], $userData['password'], $civilitys[$userData['civility']], $userData['forename'], $userData['surname'], $userData['active'], $userData['enable'], $userData['pseudonym'], $userData['slug']);
			$user->addGroup($groups[$userData['group']]);
			$manager->persist($user);
			//Flush to get the id
			$manager->flush();
			$users[$userData['mail']] = $user;
			foreach($userData['translations'] as $locale => $description) {
				$userTranslation = new UserTranslation($users[$userData['mail']], $locale, $description);
				$manager->persist($userTranslation);
				unset($userTranslation);
			}
			unset($user);
		}

		//Flush to get the ids
		$manager->flush();

		//Keyword tree
		$keywordTree = [
			'png' => [
				'en_gb' => [
					'title' => 'PNG',
					'description' => 'Portable Network Graphics (PNG) is an raster graphics file open format that supports lossless data compression'
				],
				'fr_fr' => [
					'title' => 'PNG',
					'description' => 'Le Portable Network Graphics (PNG) est un format ouvert d’images numériques, qui a été créé pour remplacer le format GIF, à l’époque propriétaire et dont la compression était soumise à un brevet'
				]
			],
			'imagick' => [
				'en_gb' => [
					'title' => 'Imagick',
					'description' => 'ImageMagick is a free and open-source software suite for displaying, converting, and editing raster image and vector image files'
				],
				'fr_fr' => [
					'title' => 'Imagick',
					'description' => 'Image Magick est une collection de logiciels libres pour afficher, convertir et modifier des images numériques matricielles ou vectorielles dans de nombreux formats'
				]
			],
			'image' => [
				'en_gb' => [
					'title' => 'Image',
					'description' => 'An image is an artifact that depicts visual perception, for example, a photo or a two-dimensional picture, that has a similar appearance to some subject'
				],
				'fr_fr' => [
					'title' => 'Image',
					'description' => 'Une image est une représentation visuelle, voire mentale, de quelque chose'
				]
			],
			'varnish' => [
				'en_gb' => [
					'title' => 'Varnish',
					'description' => 'Varnish is an HTTP cache server deployed as a reverse proxy between applications servers and clients'
				],
				'fr_fr' => [
					'title' => 'Varnish',
					'description' => 'Varnish est un serveur de cache HTTP déployé en tant que proxy inverse entre les serveurs d\'application et les clients'
				]
			],
			'webservice' => [
				'en_gb' => [
					'title' => 'Web service',
					'description' => 'A web service is a service offered by an electronic device to another electronic device, communicating with each other via the World Wide Web'
				],
				'fr_fr' => [
					'title' => 'Service web',
					'description' => 'Un service web, ou service de la toile, est un protocole d\'interface informatique de la famille des technologies web permettant la communication et l\'échange de données entre applications et systèmes hétérogènes'
				]
			],
			'rest' => [
				'en_gb' => [
					'title' => 'REST',
					'description' => 'Representational state transfer (REST) or RESTful web services are a way of providing interoperability between computer systems on the Internet'
				],
				'fr_fr' => [
					'title' => 'REST',
					'description' => 'Representational state transfer (REST) ou services web RESTful est une manière de fournir de l\'intéropérabilité entre systèmes d\'information sur Internet'
				]
			],
			'hateoas' => [
				'en_gb' => [
					'title' => 'HATEOAS',
					'description' => 'HATEOAS, abbreviation of Hypermedia As The Engine Of Application State, is a constraint of the REST application architecture that distinguishes it from other network application architectures'
				],
				'fr_fr' => [
					'title' => 'HATEOAS',
					'description' => 'HATEOAS, abréviation de Hypermedia As Engine of Application State, Hypermédia en tant que moteur de l\'état d\'application, constitue une contrainte de l\'architecture d\'application REST qui la distingue de la plupart des autres architectures d\'applications réseau'
				]
			],
			'uri' => [
				'en_gb' => [
					'title' => 'URI',
					'description' => 'In information technology, a Uniform Resource Identifier (URI) is a string of characters used to identify a resource'
				],
				'fr_fr' => [
					'title' => 'URI',
					'description' => 'En technologie de l\'information, une URI, abréviation d\'Uniform Resource Identifier, Identifiant uniforme de ressource, est une chaine de caractères utilisée pour identifier une ressource'
				]
			],
			'cidr' => [
				'en_gb' => [
					'title' => 'CIDR',
					'description' => 'Classless Inter-Domain Routing, CIDR, is a method for aggregating IP addresses and route them'
				],
				'fr_fr' => [
					'title' => 'CIDR',
					'description' => 'Routage inter-domaine sans classe, de l\'anglais Classless Inter-Domain Routing, CIDR, est une méthode pour agréger des adresses IP et les router'
				]
			],
			'amazon' => [
				'en_gb' => [
					'title' => 'Amazon',
					'description' => 'Amazon Elastic Compute Cloud or EC2 is an Amazon server renting service allowing third party to run their own web application'
				],
				'fr_fr' => [
					'title' => 'Amazon',
					'description' => 'Amazon Elastic Compute Cloud ou EC2 est un service proposé par Amazon permettant à des tiers de louer des serveurs sur lesquels exécuter leurs propres applications web'
				]
			],
			'php' => [
				'en_gb' => [
					'title' => 'PHP',
					'description' => 'PHP: Hypertext Preprocessor, better known as PHP, is an open programming language, used mostly to produce dynamic web pages through an HTTP server'
				],
				'fr_fr' => [
					'title' => 'PHP',
					'description' => 'PHP : Hypertext Preprocessor, plus connu sous son sigle PHP, est un langage de programmation libre, principalement utilisé pour produire des pages Web dynamiques via un serveur HTTP'
				]
			],
			'mysql' => [
				'en_gb' => [
					'title' => 'MySQL',
					'description' => 'MySQL is a free and open-source relational database management system'
				],
				'fr_fr' => [
					'title' => 'MySQL',
					'description' => 'MySQL est un logiciel libre de gestion de bases de données relationnelles'
				]
			],
			'azure' => [
				'en_gb' => [
					'title' => 'Azure',
					'description' => 'Microsoft Azure, formerly Windows Azure, is a cloud computing service created by Microsoft for building, testing, deploying, and managing applications and services through a global network of Microsoft-managed data centers'
				],
				'fr_fr' => [
					'title' => 'Azure',
					'description' => 'Microsoft Azure, anciennement Windows Azure, est une plateforme applicative en nuage crée par Microsoft pour construire, tester, déployer et gérer des applications et services sur un réseau global de centres de données opéré par Microsoft'
				]
			],
			'microsoft' => [
				'en_gb' => [
					'title' => 'Microsoft',
					'description' => 'Microsoft Corporation is an american multinational technology company, founded in 1975 by Bill Gates and Paul Allen'
				],
				'fr_fr' => [
					'title' => 'Microsoft',
					'description' => 'Microsoft Corporation est une multinationale informatique et micro-informatique américaine, fondée en 1975 par Bill Gates et Paul Allen'
				]
			],
			'apache' => [
				'en_gb' => [
					'title' => 'Apache',
					'description' => 'Apache is a free and open-source HTTP server for modern operating systems'
				],
				'fr_fr' => [
					'title' => 'Apache',
					'description' => 'Apache est un logiciel libre serveur HTTP pour les systèmes d\'exploitation modernes'
				]
			],
			'haproxy' => [
				'en_gb' => [
					'title' => 'HAProxy',
					'description' => 'HAProxy is a reliable high performance TCP/HTTP free and open-source load balancer'
				],
				'fr_fr' => [
					'title' => 'HAProxy',
					'description' => 'HAProxy est un logiciel libre équilibreur de charge TCP/HTTP fiable et haute performance'
				]
			],
			'http' => [
				'en_gb' => [
					'title' => 'HTTP',
					'description' => 'HTTP, abbreviation of HyperText Transfer Protocol, is a network client-server communications protocol developed for the World Wide Web'
				],
				'fr_fr' => [
					'title' => 'HTTP',
					'description' => 'HTTP, abréviation de HyperText Transfer Protocol, est un protocole de communication client-serveur réseau développé pour le World Wide Web'
				]
			],
			'proxy' => [
				'en_gb' => [
					'title' => 'Proxy',
					'description' => 'Intermediate server application between two hosts to improve privacy, security and performance'
				],
				'fr_fr' => [
					'title' => 'Proxy',
					'description' => 'Application serveur intermédiaire entre deux hôtes pour améliorer la confidentialité, la sécurité et les performances'
				]
			],
			'quic' => [
				'en_gb' => [
					'title' => 'QUIC',
					'description' => 'QUIC, abbreviation of Quick UDP Internet Connections, is a fast network client-server communications protocol over UDP developed for Google'
				],
				'fr_fr' => [
					'title' => 'QUIC',
					'description' => 'QUIC, abréviation de Quick UDP Internet Connections, est un protocole de communication client-serveur réseau rapide sur UDP développé pour Google'
				]
			],
			'mageia' => [
				'en_gb' => [
					'title' => 'Mageia',
					'description' => 'Mageia is a free operating system community project, based on GNU/Linux, supported by a French 1901 law association made up of elected contributors'
				],
				'fr_fr' => [
					'title' => 'Mageia',
					'description' => 'Mageia est un projet communautaire de système d\'exploitation libre, basé sur GNU/Linux, soutenu par une association loi 1901 française constituée de contributeurs élus'
				]
			],
			'google' => [
				'en_gb' => [
					'title' => 'Google',
					'description' => 'Google organize the world\'s information and make it universally accessible and useful'
				],
				'fr_fr' => [
					'title' => 'Google',
					'description' => 'Google organise les informations à l\'échelle mondiale pour les rendre accessibles et utiles à tous'
				]
			],
		];

		//Create 3 keywords
		$keywords = [];
		foreach($keywordTree as $name => $data) {
			$keyword = new Keyword();
			$manager->persist($keyword);
			//Flush to get the id
			$manager->flush();
			$keywords[$name] = $keyword;
			foreach($data as $locale => $translation) {
				$keywordTranslation = new KeywordTranslation($keywords[$name], $locale, $translation['description'], $this->slugger->slug($translation['title']), $translation['title']);
				$manager->persist($keywordTranslation);
				unset($keywordTranslation);
			}
			unset($keyword);
		}

		//Flush to get the ids
		$manager->flush();

		//Article tree
		$articleTree = [
			[
				'mail' => 'blog@rapsys.eu',
				'keywords' => ['image', 'imagick', 'png'],
				'translations' => [
					'en_gb' => [
						'title' => 'How to reliably detect transparency in PNG image with PHP',
						'description' => 'Reliable PHP function to detect PNG images with transparency supporting all the variants to process.',
						'body' => 'The primary need is to find out if a PNG has transparency using PHP.

All the code I found didn\'t seemed to work correctly for the collection of PNG I had to convert.

I finished using the following function:
```php
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
```

This function works with the only two transparency possibilities: 8 and 32-bit PNG.

The first case is a 32-bit PNG with transparency enabled, we have then to check every pixel to detect if it has transparent part or not.

The second case is a 8-bit PNG, then we only have to look the file content for transparency markers.

In this function configuration, we only read part of the file in 32-bit PNG until we detect one transparent pixel or parse content until transparency marker is detected in 8-bit PNG.

The worst case scenario will be 32-bit PNG with transparency flag without transparency or 8-bit PNG without transparency flag.

Depending on how likely you are to have transparency in each cases you might want to reverse the flow of this function.

Big thanks to these articles which expains how these parts work in a bit more detail:
- <https://www.jonefox.com/blog/2011/04/15/how-to-detect-transparency-in-png-images>
- <https://camendesign.com/code/uth1_is-png-32bit>
- <https://stackoverflow.com/questions/5495275/how-to-check-if-an-image-has-transparency-using-gd>

Hope this helps someone else out there.'
					],
					'fr_fr' => [
						'title' => 'Comment détecter la tranparence dans des images PNG en PHP de manière fiable',
						'description' => 'Fonction PHP fiable pour détecter les images PNG avec transparence prenant en charge toutes les variantes à traiter.',
						'body' => 'Le besoin principal est de savoir si un PNG a de la transparence en utilisant PHP.

Les codes trouvés ne semblaient pas fonctionner de manière satisfaisante pour les différents types de PNG à contrôler.

J\'ai fini par utiliser la fonction suivante:
```php
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
```

Cette fonction fonctionne avec les deux seules possibilités : PNG 8 et 32 bits.

Le premier cas est un PNG 32 bits avec transparence activée, on doit alors vérifier l\'opacité de chaque pixel savoir si l\'image a de la transparence ou non.

Le second cas est un PNG 8 bits, on a simplement à détecter un marqueur de transparence dans le contenu du fichier.

Dans cette configuration de fonction, on lit seulement une partie du PNG 32 bits jusqu\'à détection d\'un pixel transparent où on analyse le contenu jusqu\'à trouver un marqueur de transparence dans un PNG 8 bits.

Les pires cas seront un PNG 32 bits avec marqueur de transparence sans pixel transparent ou PNG 8 bits sans marqueur de transparence.

Selon les probabilités de rencontrer les différents cas de transparence vous pouvez être intéressé pour renverser l\'ordre des tests de cette fonction.

Un grand merci à ces articles qui expliquent plus en détail comment fonctionnent les différentes parties de ce code:
- <https://www.jonefox.com/blog/2011/04/15/how-to-detect-transparency-in-png-images>
- <https://camendesign.com/code/uth1_is-png-32bit>
- <https://stackoverflow.com/questions/5495275/how-to-check-if-an-image-has-transparency-using-gd>

En espérant que cela puisse aider quelques personnes.'
					]
				]
			],
			[
				'mail' => 'blog@rapsys.eu',
				'keywords' => ['hateoas', 'http', 'rest', 'uri', 'varnish', 'webservice'],
				'translations' => [
					'en_gb' => [
						'title' => 'Caching webservice with Varnish',
						'description' => 'Cache a webservice responses by ignoring the Authorization header using Varnish.',
						'body' => 'The primary goal is to find a way to reduce the load of a webservice by caching its responses.

The webservice is a RESTfull API serving as a gateway between a private HATEOAS API and a client generating more than 500 000 requests a day.

The first surprise is that if your well educated client, sending you a header Authorization: Bearer, will not be cached by default by Varnish !

Let\'s force back the standard behaviour with this header for our webservice uri prefix:

```varnish
sub vcl_recv {
        # Force cache response even with req.http.Authorization set
        if (req.http.Authorization) {
                if (req.url ~ "^/webservice/uri/prefix/") {
                        return (lookup);
                }
        }
}
```

This has security implication, because anyone allowed to request varnish will be able to retrieve a cached result without authentification.

It is important to validate the Authorization header value before serving the result from cache.

Now, our webservice has three possibles answers :
- 200: the data in JSON
- 404: data was not found
- 410: data is not available anymore

Let\'s cache our results depending on the reponse code:

```varnish
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
```

With this configuration, we divided by 5 the quantity of request on our gateway from the client who was not able to cache our result himself.'
					],
					'fr_fr' => [
						'title' => 'Mise en cache de webservice avec Varnish',
						'description' => 'Mettre en cache les réponses d\'un service web en ignorant l’en-tête Authorization à l’aide de Varnish.',
						'body' => 'Le but premier est de trouver une solution pour réduire la charge d\'un service web en mettant en cache ses réponses.

L\'API RESTfull du webservice sert de passerelle entre un API privé HATEOAS et un client générant plus de 500 000 requêtes par jour.

La première surprise est qu\'un client bien élevé, envoyant un en-tête Authorization: Bearer, ne sera pas mis en cache par Varnish par défaut !

Forçons le fonctionnement standard avec l\'en-tête pour le préfixe de l\'uri de notre webservice:

```varnish
sub vcl_recv {
        # Force la mise en cache de la réponse même avec req.http.Authorization présent
        if (req.http.Authorization) {
                if (req.url ~ "^/webservice/uri/prefix/") {
                        return (lookup);
                }
        }
}
```

Ce changement a des conséquences sur la sécurité, puisque n\'importe quelle personne autorisée à interroger Varnish sera en mesure de récupérer un résultat en cache sans s\'identifier.

Il est important de valider la valeur de l\'en-tête Authorization avant de fournir le résultat depuis le cache.

Notre webservice a trois réponses possibles :
- 200: les données en JSON
- 404: données non trouvées
- 410: données plus jamais disponibles

Mettons en cache les résultats selon le code de retour :

```varnish
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
```

Avec cette configuration, on a divisé par 5 la quantité de demandes sur notre passerelle pour le client qui n\'était pas en mesure de mettre en cache lui-même nos résultats.'
					]
				]
			],
			[
				'mail' => 'blog@rapsys.eu',
				'keywords' => ['amazon', 'azure', 'cidr', 'http', 'microsoft', 'mysql', 'php', 'webservice', 'google'],
				'translations' => [
					'en_gb' => [
						'title' => 'Dealing with IP range in PHP/MySQL',
						'description' => 'Secure a webservice by granting access only to remote IP address included in a CIDR blocks set.',
						'body' => 'The goal is to process some CIDR blocks to tighten a webservice security.

First let\'s see how to compute the first and last address of an IP range with just the block base IP and mask:

```php
$range = [\'127.0.0.1\', 8];
function rangeBegin($range) {
        return $range[0];
}
function rangeEnd($range) {
        return long2ip(ip2long($range[0]) | ((1 << (32 - $range[1])) - 1));
}
```

How to detect if an IP is present in a CIDR block:

```php
$ip = \'127.0.0.1\';
$range = [\'127.0.0.1\', 8];
function ipInRange($ip, $range) {
        if (ip2long($range[0]) <= ip2long($ip) && ip2long($ip) <= (ip2long($range[0]) | ((1 << (32 - $range[1])) - 1))) {
                return true;
        }
        return false;
}
```

As a first bonus, how to retrieve amazon IP ranges:

```php
function fetchAmazonRange() {
        //Init array
        $amazonRanges = [];

        $ctx = stream_context_create(
                [
                        \'http\' => [
                                \'method\' => \'GET\',
                                \'max_redirects\' => 0,
                                \'timeout\' => 5,
                                \'ignore_errors\' => false,
                                \'header\' => [
                                        \'Connection: close\',
                                        \'Accept: application/json\'
                                ]
                        ]
                ]
        ];

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
```

Microsoft Azure IP ranges urls:
- <https://www.microsoft.com/en-us/download/details.aspx?id=41653>
- <https://msdn.microsoft.com/library/mt757330.aspx>

Google IP ranges urls:
- <https://support.google.com/a/answer/10026322?hl=en>'
					],
					'fr_fr' => [
						'title' => 'Gestion des plages d\'IP en PHP/MySQL',
						'description' => 'Sécuriser un service web en accordant l\'accès uniquement à l\'adresse IP distante incluse dans un jeu de blocs CIDR.',
						'body' => 'L\'objectif est de traiter des blocs CIDR pour renforcer la sécurité d’un service web.

Premièrement, voyons comment calculer la première et la dernière adresse IP d\'une plage (bloc CIDR) avec sa base et son masque :

```php
$range = [\'127.0.0.1\', 8];
function rangeBegin($range) {
        return $range[0];
}
function rangeEnd($range) {
        return long2ip(ip2long($range[0]) | ((1 << (32 - $range[1])) - 1));
}
```

Maintenant comment vérifier si une IP est présente dans une plage (bloc CIDR) :

```php
$ip = \'127.0.0.1\';
$range = [\'127.0.0.1\', 8];
function ipInRange($ip, $range) {
        if (ip2long($range[0]) <= ip2long($ip) && ip2long($ip) <= (ip2long($range[0]) | ((1 << (32 - $range[1])) - 1))) {
                return true;
        }
        return false;
}
```

En premier bonus, comment récupérer les plages d\'IP d\'amazon :

```php
function fetchAmazonRange() {
        //Init array
        $amazonRanges = [];

        $ctx = stream_context_create(
                [
                        \'http\' => [
                                \'method\' => \'GET\',
                                \'max_redirects\' => 0,
                                \'timeout\' => 5,
                                \'ignore_errors\' => false,
                                \'header\' => [
                                        \'Connection: close\',
                                        \'Accept: application/json\'
                                ]
                        ]
                ]
        ];

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
```

Urls pour les plages d\'IP de Microsoft Azure :
- <https://www.microsoft.com/en-us/download/details.aspx?id=41653>
- <https://msdn.microsoft.com/library/mt757330.aspx>

Urls pour les plages d\'IP de Google :
- <https://support.google.com/a/answer/10026322?hl=en>'
					]
				]
			],
			[
				'mail' => 'blog@rapsys.eu',
				'keywords' => ['apache', 'haproxy', 'http', 'mageia', 'proxy', 'quic'],
				'translations' => [
					'en_gb' => [
						'title' => 'A load balancer to standardize access to heterogeneous web services',
						'description' => 'Setup a single entry point for various web services including Apache and a few others available on exotic ports.',
						'body' => 'The primary need is to set up a single entry point for different web services including Apache and a few others on exotic ports.

Like any new project, implementation, as usual, took longer than initially expected.

To this end, I packaged the HAProxy service in the Mageia Linux distribution with a transparent proxy configuration by default.

HAProxy does not support the certificate path layout used on Red Hat-derived distributions, where public and private keys are separated into two separate files in two different directories.

HAProxy suffers from a critical bug when using standard output for logging which resumes at the beginning of the file after a restart.

HAProxy is penalized by disabling the buffer on its standard output when used for logging.

Three fixes were made by me for this purpose to resolve these problems:
- support for certificate path layout for Red Hat distribution
- no longer starts at the beginning of the log file after a restart
- no longer deactivate the standard output buffer

New features are being developed for the next version of HAProxy that could make my choices and developments on logging obsolete.

To make support of the HTTP/3 protocol possible by HAProxy, it was necessary to integrate the quictls library, a branch of OpenSSL with support for the QUIC protocol.

To install the server:
```bash
# urpmi haproxy haproxy-quic
```

Activate the service:
```bash
# systemctl enable haproxy.service
```

Start the service:
```bash
# systemctl start haproxy.service
```

In order to avoid a waltz of ports when integrating into an existing architecture, port indirection in Shorewall is proposed:
```shorewall
# Redirect tcp traffic from net on port 80 to 8000
REDIRECT net 8000 tcp 80
# Redirect tcp traffic from net on port 443 to 8000
REDIRECT net 8000 tcp 443
# Redirect udp traffic from net on port 443 to 8443
REDIRECT net 8443 udp 443
```

This allows HAProxy to capture HTTP and HTTPS traffic on port 8000/TCP and QUIC on port 8443/UDP.

The default configuration captures HTTP and HTTPS traffic on the tcp_default front end, then switches it to the tcp_http and tcp_https back ends, the latter will send the flows to the http_default and https_default front ends, taking care to add a proxy header to transmit the original IP address and port. This configuration allows other TCP services (SSH, VPN, etc.) to be captured and redistributed as needed.

The traffic is then received by the http_default and https_default front-ends respectively, the original IP address and port is preserved via the proxy header received from the TCP back-ends.

They can advertise HTTP/3 support, clean up some headers, deny access to bots, forward original IP addresses and ports via a Forwarded header, and distribute to different backends based on hosts and requested paths.

Configuring Apache to receive the proxy header is problematic, it\'s simpler to pass the Forwarded header than to configure the freshly released mod_remoteip.

Support for the HTTP/3 protocol is announced by sending an alt-svc: h3="<host>:<port>"; ma=<timeout> where host can be empty, port=443 and timeout=3600 for example. The certificate served must match the initial host name and not the offload server for security reasons.

Configuring the different http backends will come down to declaring the offload servers, the additional headers to add, the compression according to the content types and the method to test their health.

Without further ado, the configuration to adapt to your needs:
```haproxy
# HAProxy configuration file

# Global config
global
	# Log to systemd
	log stdout format short daemon
	# Number of threads
	nbthread 8
	# Pid file
	pidfile /run/haproxy/haproxy.pid

	# Stat socket
	stats socket /run/haproxy/haproxy.sock mode 0660 level admin
	# Stat timeout
	stats timeout 10s
	# Max connections
	stats maxconn 10

	# Certificate base dir
	crt-base /etc/pki/tls/certs
	# Private key base dir
	key-base /etc/pki/tls/private
	# Don\'t load extra files
	ssl-load-extra-files none
	# Do not verify certificate
	ssl-server-verify none
	# Supported bind ciphersuites
	#XXX: https://wiki.mozilla.org/Security/Server_Side_TLS#Recommended-configurations
	ssl-default-bind-ciphersuites TLS_AES_128_GCM_SHA256:TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256
	# Disable SSL-v3 TLSv1.0 TLSv1.1 and TLSv1.2 without TLS tickets
	ssl-default-bind-options ssl-min-ver TLSv1.3

	# SSL/TLS session cache size
	tune.ssl.cachesize 20000
	# SSL/TLS session life time in cache
	tune.ssl.lifetime 300
	# SSL/TLS layer maximum passed bytes at a time
	tune.ssl.maxrecord 0
	# Diffie-Hellman ephemeral keys max size
	tune.ssl.default-dh-param 2048
	# Buffer size
	tune.bufsize 16384
	# Reserved buffer size
	tune.maxrewrite 1024
	# Max number of headers
	tune.http.maxhdr 101


# Default config
defaults
	# Use global log
	log global

	# Set balance mode
	balance random
	# Set http mode
	mode http
	# Set http keep alive mode
	#XXX: https://cbonte.github.io/haproxy-dconv/2.3/configuration.html#4
	option http-keep-alive
	# Dont log empty line
	option dontlognull
	# Dissociate client from dead server
	option redispatch

	# Number of retries on connection failure
	retries 3
	# Max concurrent connections
	maxconn 30000
	# Max pending connections
	backlog 30000

	# Max time for a connection attempt
	timeout connect 5s
	# Max inactivitiy time on client side
	timeout client 60s
	# Max inactivitiy time on server side
	timeout server 60s
	# Max inactivitiy time on client and server for tunnels
	timeout tunnel 3600s
	# Max time for new request
	timeout http-keep-alive 1s
	# Max time for a complete http request
	timeout http-request 15s
	# Max time for a free slot
	timeout queue 5s
	# Duration for tarpitted connections
	timeout tarpit 5s

	# Error documents
	errorfile 400 /usr/share/doc/haproxy/error/400.http
	errorfile 403 /usr/share/doc/haproxy/error/403.http
	errorfile 408 /usr/share/doc/haproxy/error/408.http
	errorfile 500 /usr/share/doc/haproxy/error/500.http
	errorfile 502 /usr/share/doc/haproxy/error/502.http
	errorfile 503 /usr/share/doc/haproxy/error/503.http
	errorfile 504 /usr/share/doc/haproxy/error/504.http


# Default tcp frontend
frontend tcp_default
	# Set tcp mode
	mode tcp
	# Bind to 8000 port
	bind :::8000
	# Log disabled
	no log
	# Set tcp log
	#option tcplog
	# Set inspect delay
	tcp-request inspect-delay 5s
	# Wait for extension detection
	#tcp-request content accept if { req.proto_http } or { req.ssl_hello_type 1 } or { req.ssl_ec_ext 1 }
	tcp-request content accept if { req.proto_http } or { req.ssl_hello_type 1 }

	# Send to https tcp backend
	use_backend tcp_https if { req.ssl_hello_type 1 }

	# Send to ec tcp backend
	#use_backend tcp_ec if { req.ssl_ec_ext 1 }

	# Send to OpenVPN backend
	#acl openvpn payload(0,2) -m bin 003c
	#tcp-request content accept if openvpn
	#use_backend tcp_openvpn if openvpn

	# Send to OpenSSH backend
	#XXX: https://jonnyzzz.com/blog/2017/05/24/ssh-haproxy/
	#XXX: https://issues.apache.org/jira/browse/SSHD-656
	#acl ssh payload(0,7) -m str SSH-2.0
	#tcp-request content accept if ssh
	#use_backend tcp_ssh if ssh

	# Send to http tcp backend (if { req.proto_http })
	default_backend tcp_http


# Http tcp backend
backend tcp_http
	# Set tcp mode
	mode tcp
	# Send to localhost without ssl with v2 proxy header
	server haproxy 127.0.0.1:8080 no-ssl verify none send-proxy-v2


# Https tcp backend
backend tcp_https
	# Set tcp mode
	mode tcp
	# Send to localhost without ssl with v2 proxy header
	server haproxy 127.0.0.1:8443 no-ssl verify none send-proxy-v2-ssl


# Default http frontend
frontend http_default
	# Bind to 8080 port
	bind :::8080 accept-proxy
	# Insert X-Forwarded-For header
	option forwardfor
	# Set http log format
	option httplog
	# Log enabled
	log global

	# Check if acme challenge
	acl acme_challenge path_beg /.well-known/acme-challenge/

	# Add X-Backend header
	#http-response add-header X-Backend %[haproxy.backend_name]

	# Advertise QUIC
	#http-response add-header alt-svc \'h3=":443"; ma=3600\'
	#http-after-response add-header alt-svc \'h3=":443"; ma=3600\'

	# Remove server and x-powered-by headers
	#http-after-response del-header server
	#http-after-response del-header x-powered-by

	# Redirect to https scheme when unsecure and not acme challenge
	http-request redirect scheme https code 302 unless { ssl_fc } || acme_challenge

	# Check if denied path
	#XXX: use ,url_dec like in https://serverfault.com/questions/754752/block-specific-url-in-haproxy-url-encoding
	#acl denied_path path_reg ^/(\.env|login|admin/|wp-login\.php|\.git/config)$
	# Deny access on denied path
	#http-request deny if denied_path

	# Check if protected path
	#acl protected_path path_reg ^/(contact|register)$
	# Deny access on protected path
	#http-request deny deny_status 503 if protected_path { method \'POST\' } { req.ver \'1.0\' }

	# Store origin variable as txn
	http-request set-var(txn.origin) req.hdr(Origin)
	# Store host variable as txn
	http-request set-var(txn.host) req.hdr(Host),field(1,:),lower
	# Store proto variable as txn
	http-request set-var(txn.proto) ssl_fc,iif(https,http)

	# Set forwarded proto
	http-request set-header X-Forwarded-Proto %[var(txn.proto)]
	# Set forwarded port
	http-request set-header X-Forwarded-Port %[dst_port]
	# Set forwarded for
	#http-request set-header X-Forwarded-For %[src]
	# Set forwarded by
	http-request set-header X-Forwarded-By %[dst]

	# Set forwarded
	#http-request set-header Forwarded by=%[dst]:%[dst_port];for=%[src]:%[src_port];host=%[var(txn.host)];proto=%[var(txn.proto)]
	http-request set-header Forwarded by=%[dst]:%[dst_port];for=%[src]:%[src_port];proto=%[var(txn.proto)]

	# Check if host is cdn.example.com
	acl cdn var(txn.host) -m str cdn.example.com
	# Check if cdn css path
	acl cdn_css path_beg /css
	# Check if cdn js path
	acl cdn_js path_beg /js
	# Check if haproxy status path
	acl haproxy_status path_beg /haproxy-status
	# Check if debug path
	acl debug path_beg /debug

	# Send to css backend if path start with /css
	use_backend http_css if cdn cdn_css
	# Send to js backend if path start with /js
	use_backend http_js if cdn cdn_js
	# Send to status backend if path start with /haproxy-status
	use_backend http_status if haproxy_status
	# Send to debug backend if path start with /debug
	use_backend http_debug if debug

	# Send to https backend
	use_backend https_default if { ssl_fc }

	# Send to default backend
	default_backend http_default

# Default https frontend
#XXX: copy of upper one, just done to skip logs here
frontend https_default
	# Bind to 8443 tcp port as ssl
	bind :::8443 ssl crt haproxy.pem alpn h2,http/1.1,http/1.0 accept-proxy
	# Bind to 8443 udp port as ssl
	#bind quic6@:::8443 ssl crt haproxy.pem alpn h3
	# Insert X-Forwarded-For header
	option forwardfor
	# Set http log format
	option httplog
	# Log enabled
	log global

	# Check if acme challenge
	acl acme_challenge path_beg /.well-known/acme-challenge/

	# Add X-Backend header
	#http-response add-header X-Backend %[haproxy.backend_name]

	# Advertise QUIC
	#http-response add-header alt-svc \'h3=":443"; ma=3600\'
	#http-after-response add-header alt-svc \'h3=":443"; ma=3600\'

	# Remove server and x-powered-by headers
	#http-after-response del-header server
	#http-after-response del-header x-powered-by

	# Redirect to https scheme when unsecure and not acme challenge
	http-request redirect scheme https code 302 unless { ssl_fc } || acme_challenge

	# Check if denied path
	#XXX: use ,url_dec like in https://serverfault.com/questions/754752/block-specific-url-in-haproxy-url-encoding
	#acl denied_path path_reg ^/(\.env|login|admin/|wp-login\.php|\.git/config)$
	# Deny access on denied path
	#http-request deny if denied_path

	# Check if protected path
	#acl protected_path path_reg ^/(contact|register)$
	# Deny access on protected path
	#http-request deny deny_status 503 if protected_path { method \'POST\' } { req.ver \'1.0\' }

	# Store origin variable as txn
	http-request set-var(txn.origin) req.hdr(Origin)
	# Store host variable as txn
	http-request set-var(txn.host) req.hdr(Host),field(1,:),lower
	# Store proto variable as txn
	http-request set-var(txn.proto) ssl_fc,iif(https,http)

	# Set forwarded proto
	http-request set-header X-Forwarded-Proto %[var(txn.proto)]
	# Set forwarded port
	http-request set-header X-Forwarded-Port %[dst_port]
	# Set forwarded for
	#http-request set-header X-Forwarded-For %[src]
	# Set forwarded by
	http-request set-header X-Forwarded-By %[dst]

	# Set forwarded
	#http-request set-header Forwarded by=%[dst]:%[dst_port];for=%[src]:%[src_port];host=%[var(txn.host)];proto=%[var(txn.proto)]
	http-request set-header Forwarded by=%[dst]:%[dst_port];for=%[src]:%[src_port];proto=%[var(txn.proto)]

	# Check if host is cdn.example.com
	acl cdn var(txn.host) -m str cdn.example.com
	# Check if cdn css path
	acl cdn_css path_beg /css
	# Check if cdn js path
	acl cdn_js path_beg /js
	# Check if haproxy status path
	acl haproxy_status path_beg /haproxy-status
	# Check if debug path
	acl debug path_beg /debug

	# Send to css backend if path start with /css
	use_backend http_css if cdn cdn_css
	# Send to js backend if path start with /js
	use_backend http_js if cdn cdn_js
	# Send to status backend if path start with /haproxy-status
	use_backend http_status if haproxy_status
	# Send to debug backend if path start with /debug
	use_backend http_debug if debug

	# Send to https backend
	use_backend https_default if { ssl_fc }

	# Send to default backend
	default_backend http_default


# Debug http backend
backend http_debug
	# Check if trusted
	acl trusted src 127.0.0.0/8 ::1
	# Allow access from trusted only
	http-request deny unless trusted
	# Server without ssl or check
	server debug 127.0.0.1:8090 no-ssl verify none


# Default http backend
backend http_default
	# Enable check
	option httpchk
	# User server default
	http-check connect default
	# Send HEAD on / with protocol HTTP/1.1 for host example.com
	http-check send meth HEAD uri / ver HTTP/1.1 hdr Host example.com
	# Expect return code between 200 and 399
	http-check expect status 200-399

	# Insert header X-Server: apache
	#http-response add-header X-Server apache

	# Set compression algorithm
	#compression algo gzip
	# Enable compression for html, plain and css text types
	#compression type text/html text/plain text/css

	# Server with ssl and check without certificate verification
	server apache 127.0.0.1:80 no-ssl verify none check #cookie apache


# Default https backend
backend https_default
	# Enable check
	option httpchk
	# User server default
	http-check connect default
	# Send HEAD on / with protocol HTTP/1.1 for host example.com
	http-check send meth HEAD uri / ver HTTP/1.1 hdr Host example.com
	# Expect return code between 200 and 399
	http-check expect status 200-399

	# Insert header X-Server: apache
	#http-response add-header X-Server apache

	# Force HSTS for 5 minutes on domain and all subdomains
	#http-response set-header Strict-Transport-Security max-age=300#;\ includeSubDomains#;\ preload

	# Set compression algorithm
	#compression algo gzip
	# Enable compression for html, plain and css text types
	#compression type text/html text/plain text/css

	# Server with ssl and check without certificate verification
	server apache 127.0.0.1:443 ssl verify none check #cookie apache


# Css http backend
backend http_css
	# Enable check
	option httpchk
	# User server default
	http-check connect default
	# Send GET on /css/empty.css with protocol HTTP/1.1 for host cdn.example.com
	http-check send meth GET uri /css/empty.css ver HTTP/1.1 hdr Host cdn.example.com
	# Expect return code between 200 and 399
	http-check expect status 200-399

	# Server with check without ssl and certificate verification
	server css 127.0.0.1:80 no-ssl verify none check


# Js http backend
backend http_js
	# Enable check
	option httpchk
	# User server default
	http-check connect default
	# Send HEAD on /js/missing.js with protocol HTTP/1.1 for host cdn.example.com
	http-check send meth HEAD uri /js/missing.js ver HTTP/1.1 hdr Host cdn.example.com
	# Expect return code 404
	http-check expect status 404

	# Check if txn.origin start with https://cdn.example.com
	acl cdn_origin var(txn.origin) -m beg https://cdn.example.com
	# Send origin as ACAO
	http-response set-header Access-Control-Allow-Origin %[var(txn.origin)] if cdn_origin
	# Set ACMA for one day
	http-response set-header Access-Control-Max-Age 86400 if cdn_origin

	# Server with check without ssl and certificate verification
	server js 127.0.0.1:80 no-ssl verify none check


# Status user list
userlist status
	# Add user admin
	user admin insecure-password ADMINPASSWORD
	# Add user operator
	user operator insecure-password OPERATORPASSWORD
	# Assign admin in admin group
	group admin users admin
	# Assign operator and admin in operator group
	group operator users operator,admin


# Status http backend
backend http_status
	# Add operator acl
	acl is_operator http_auth(status)
	# Add admin acl
	acl is_admin http_auth_group(status) admin
	# Check if trusted
	acl trusted src 127.0.0.0/8 ::1
	# Enable stats
	stats enable
	# Set stats hook on /haproxy-status
	stats uri /haproxy-status
	# Set refresh time
	stats refresh 10s
	# Display legends
	stats show-legends
	# Display node
	stats show-node
	# Allow access from trusted or authentified operator only
	#stats http-request auth unless trusted or is_operator
	stats http-request auth unless trusted
	# Activate admin interface from trusted or authentified admin only
	#stats admin if is_admin
```

Hope this article was helpful to you.'
					],
					'fr_fr' => [
						'title' => 'Un équilibreur de charge pour standardiser l\'accès aux services web hétérogènes',
						'description' => 'Configurer un point d\'entrée unique pour différents services web dont Apache et quelques autres disponibles sur des ports exotiques.',
						'body' => 'Le besoin principal est de mettre en place un point d\'entrée unique pour différents services web dont Apache et quelques autres sur des ports exotiques.

Comme tout nouveau projet, la mise en œuvre, comme d\'habitude, a nécessité plus de temps que prévu initialement.

À cet effet, j\'ai réalisé la mise en paquet du service HAProxy dans la distribution linux Mageia avec une configuration de proxy transparent par défaut.

HAProxy ne prend pas en charge la disposition de chemin des certificats utilisée sur les distributions dérivées de Red Hat, où clef publique et privée sont séparées en deux fichiers distincts dans deux répertoires différents.

HAProxy souffre d\'un bug critique lors de l\'usage de la sortie standard pour la journalisation qui reprend en début de fichier après un redémarage.

HAProxy est pénalisé par la désactivation du tampon sur sa sortie standard lorsqu\'elle est utilisée pour la journalisation.

Trois correctifs ont été réalisé par mes soins à cet effet pour résoudre ces problèmes sur le chemin de mes besoins :
- prise en charge de la disposition de chemin des certificats pour distribution Red Hat
- ne recommence plus au début du fichier journal après un redémarage
- ne plus désactiver le tampon de la sortie standard

De nouvelles fonctionnalités sont en cours de développement pour la prochaine version d\'HAProxy qui pourraient rendre obsolètes mes choix et développements sur la journalisation.

Pour rendre possible la prise en charge du protocole HTTP/3 par HAProxy, il a été nécessaire d\'intégrer la librairie quictls, une branche d\'OpenSSL avec support du protocole QUIC.

Pour installer le serveur :
```bash
# urpmi haproxy haproxy-quic
```

Activer le service :
```bash
# systemctl enable haproxy.service
```

Démarrer le service :
```bash
# systemctl start haproxy.service
```

Afin d\'éviter une valse de ports lors de l\'intégration dans une architecture existante, l\'indirection de ports dans Shorewall est proposée :
```shorewall
# Redirect tcp traffic from net on port 80 to 8000
REDIRECT	net	8000	tcp	80
# Redirect tcp traffic from net on port 443 to 8000
REDIRECT	net	8000	tcp	443
# Redirect udp traffic from net on port 443 to 8443
REDIRECT	net	8443	udp	443
```

Cela permet à HAProxy de capturer le trafic HTTP et HTTPS sur le port 8000/TCP et QUIC sur le port 8443/UDP.

La configuration par défaut capture le trafic HTTP et HTTPS sur le frontal tcp_default, puis le bascule sur les dorsaux tcp_http et tcp_https, ces derniers enverront les flux sur les frontaux http_default et https_default, en prenant soin d\'ajouter un en-tête proxy pour transmettre l\'adresse IP et le port d\'origine. Cette configuration permet à d\'autre services TCP (SSH, VPN, etc) d\'être capturés et redistribués au besoin.

Le trafic est ensuite reçu respectivement par les frontaux http_default et https_default, l\'adresse IP et le port d\'origine est conservée via l\'en-tête proxy reçu des dorsaux TCP.

Ils peuvent annoncer la prise en charge de HTTP/3, nettoyer certains en-têtes, refuser l\'accès aux robots, transférer les adresses IP et ports d\'origine via un en-tête Forwarded et distribuer à différents dorsaux en fonction des hôtes et chemins demandés.

Configurer Apache pour recevoir l\'en-tête proxy est problématique, il est plus simple de transmettre l\'en-tête Forwarded que de configurer le fraîchement publié mod_remoteip.

La prise en charge du protocole HTTP/3 est annoncée par l\'envoi d\'un en-tête alt-svc: h3="<host>:<port>"; ma=<timeout> où host peut être vide, port=443 et timeout=3600 par exemple. Le certificat servi doit correspondre au nom d\'hôte initial et non à celui du serveur de déchargement pour des raisons de sécurité.

Configurer les différents backends http se résumera à déclarer les serveurs de déchargement, les en-têtes supplémentaires à ajouter, la compression selon les types de contenus et la méthode pour tester leur santé.

Sans plus attendre la configuration à adapter à vos besoins :
```haproxy
# HAProxy configuration file

# Global config
global
	# Log to systemd
	log stdout format short daemon
	# Number of threads
	nbthread 8
	# Pid file
	pidfile /run/haproxy/haproxy.pid

	# Stat socket
	stats socket /run/haproxy/haproxy.sock mode 0660 level admin
	# Stat timeout
	stats timeout 10s
	# Max connections
	stats maxconn 10

	# Certificate base dir
	crt-base /etc/pki/tls/certs
	# Private key base dir
	key-base /etc/pki/tls/private
	# Don\'t load extra files
	ssl-load-extra-files none
	# Do not verify certificate
	ssl-server-verify none
	# Supported bind ciphersuites
	#XXX: https://wiki.mozilla.org/Security/Server_Side_TLS#Recommended-configurations
	ssl-default-bind-ciphersuites TLS_AES_128_GCM_SHA256:TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256
	# Disable SSL-v3 TLSv1.0 TLSv1.1 and TLSv1.2 without TLS tickets
	ssl-default-bind-options ssl-min-ver TLSv1.3

	# SSL/TLS session cache size
	tune.ssl.cachesize 20000
	# SSL/TLS session life time in cache
	tune.ssl.lifetime 300
	# SSL/TLS layer maximum passed bytes at a time
	tune.ssl.maxrecord 0
	# Diffie-Hellman ephemeral keys max size
	tune.ssl.default-dh-param 2048
	# Buffer size
	tune.bufsize 16384
	# Reserved buffer size
	tune.maxrewrite 1024
	# Max number of headers
	tune.http.maxhdr 101


# Default config
defaults
	# Use global log
	log global

	# Set balance mode
	balance random
	# Set http mode
	mode http
	# Set http keep alive mode
	#XXX: https://cbonte.github.io/haproxy-dconv/2.3/configuration.html#4
	option http-keep-alive
	# Dont log empty line
	option dontlognull
	# Dissociate client from dead server
	option redispatch

	# Number of retries on connection failure
	retries 3
	# Max concurrent connections
	maxconn 30000
	# Max pending connections
	backlog 30000

	# Max time for a connection attempt
	timeout connect 5s
	# Max inactivitiy time on client side
	timeout client 60s
	# Max inactivitiy time on server side
	timeout server 60s
	# Max inactivitiy time on client and server for tunnels
	timeout tunnel 3600s
	# Max time for new request
	timeout http-keep-alive 1s
	# Max time for a complete http request
	timeout http-request 15s
	# Max time for a free slot
	timeout queue 5s
	# Duration for tarpitted connections
	timeout tarpit 5s

	# Error documents
	errorfile 400 /usr/share/doc/haproxy/error/400.http
	errorfile 403 /usr/share/doc/haproxy/error/403.http
	errorfile 408 /usr/share/doc/haproxy/error/408.http
	errorfile 500 /usr/share/doc/haproxy/error/500.http
	errorfile 502 /usr/share/doc/haproxy/error/502.http
	errorfile 503 /usr/share/doc/haproxy/error/503.http
	errorfile 504 /usr/share/doc/haproxy/error/504.http


# Default tcp frontend
frontend tcp_default
	# Set tcp mode
	mode tcp
	# Bind to 8000 port
	bind :::8000
	# Log disabled
	no log
	# Set tcp log
	#option tcplog
	# Set inspect delay
	tcp-request inspect-delay 5s
	# Wait for extension detection
	#tcp-request content accept if { req.proto_http } or { req.ssl_hello_type 1 } or { req.ssl_ec_ext 1 }
	tcp-request content accept if { req.proto_http } or { req.ssl_hello_type 1 }

	# Send to https tcp backend
	use_backend tcp_https if { req.ssl_hello_type 1 }

	# Send to ec tcp backend
	#use_backend tcp_ec if { req.ssl_ec_ext 1 }

	# Send to OpenVPN backend
	#acl openvpn payload(0,2) -m bin 003c
	#tcp-request content accept if openvpn
	#use_backend tcp_openvpn if openvpn

	# Send to OpenSSH backend
	#XXX: https://jonnyzzz.com/blog/2017/05/24/ssh-haproxy/
	#XXX: https://issues.apache.org/jira/browse/SSHD-656
	#acl ssh payload(0,7) -m str SSH-2.0
	#tcp-request content accept if ssh
	#use_backend tcp_ssh if ssh

	# Send to http tcp backend (if { req.proto_http })
	default_backend tcp_http


# Http tcp backend
backend tcp_http
	# Set tcp mode
	mode tcp
	# Send to localhost without ssl with v2 proxy header
	server haproxy 127.0.0.1:8080 no-ssl verify none send-proxy-v2


# Https tcp backend
backend tcp_https
	# Set tcp mode
	mode tcp
	# Send to localhost without ssl with v2 proxy header
	server haproxy 127.0.0.1:8443 no-ssl verify none send-proxy-v2-ssl


# Default http frontend
frontend http_default
	# Bind to 8080 port
	bind :::8080 accept-proxy
	# Insert X-Forwarded-For header
	option forwardfor
	# Set http log format
	option httplog
	# Log enabled
	log global

	# Check if acme challenge
	acl acme_challenge path_beg /.well-known/acme-challenge/

	# Add X-Backend header
	#http-response add-header X-Backend %[haproxy.backend_name]

	# Advertise QUIC
	#http-response add-header alt-svc \'h3=":443"; ma=3600\'
	#http-after-response add-header alt-svc \'h3=":443"; ma=3600\'

	# Remove server and x-powered-by headers
	#http-after-response del-header server
	#http-after-response del-header x-powered-by

	# Redirect to https scheme when unsecure and not acme challenge
	http-request redirect scheme https code 302 unless { ssl_fc } || acme_challenge

	# Check if denied path
	#XXX: use ,url_dec like in https://serverfault.com/questions/754752/block-specific-url-in-haproxy-url-encoding
	#acl denied_path path_reg ^/(\.env|login|admin/|wp-login\.php|\.git/config)$
	# Deny access on denied path
	#http-request deny if denied_path

	# Check if protected path
	#acl protected_path path_reg ^/(contact|register)$
	# Deny access on protected path
	#http-request deny deny_status 503 if protected_path { method \'POST\' } { req.ver \'1.0\' }

	# Store origin variable as txn
	http-request set-var(txn.origin) req.hdr(Origin)
	# Store host variable as txn
	http-request set-var(txn.host) req.hdr(Host),field(1,:),lower
	# Store proto variable as txn
	http-request set-var(txn.proto) ssl_fc,iif(https,http)

	# Set forwarded proto
	http-request set-header X-Forwarded-Proto %[var(txn.proto)]
	# Set forwarded port
	http-request set-header X-Forwarded-Port %[dst_port]
	# Set forwarded for
	#http-request set-header X-Forwarded-For %[src]
	# Set forwarded by
	http-request set-header X-Forwarded-By %[dst]

	# Set forwarded
	#http-request set-header Forwarded by=%[dst]:%[dst_port];for=%[src]:%[src_port];host=%[var(txn.host)];proto=%[var(txn.proto)]
	http-request set-header Forwarded by=%[dst]:%[dst_port];for=%[src]:%[src_port];proto=%[var(txn.proto)]

	# Check if host is cdn.example.com
	acl cdn var(txn.host) -m str cdn.example.com
	# Check if cdn css path
	acl cdn_css path_beg /css
	# Check if cdn js path
	acl cdn_js path_beg /js
	# Check if haproxy status path
	acl haproxy_status path_beg /haproxy-status
	# Check if debug path
	acl debug path_beg /debug

	# Send to css backend if path start with /css
	use_backend http_css if cdn cdn_css
	# Send to js backend if path start with /js
	use_backend http_js if cdn cdn_js
	# Send to status backend if path start with /haproxy-status
	use_backend http_status if haproxy_status
	# Send to debug backend if path start with /debug
	use_backend http_debug if debug

	# Send to https backend
	use_backend https_default if { ssl_fc }

	# Send to default backend
	default_backend http_default

# Default https frontend
#XXX: copy of upper one, just done to skip logs here
frontend https_default
	# Bind to 8443 tcp port as ssl
	bind :::8443 ssl crt haproxy.pem alpn h2,http/1.1,http/1.0 accept-proxy
	# Bind to 8443 udp port as ssl
	#bind quic6@:::8443 ssl crt haproxy.pem alpn h3
	# Insert X-Forwarded-For header
	option forwardfor
	# Set http log format
	option httplog
	# Log enabled
	log global

	# Check if acme challenge
	acl acme_challenge path_beg /.well-known/acme-challenge/

	# Add X-Backend header
	#http-response add-header X-Backend %[haproxy.backend_name]

	# Advertise QUIC
	#http-response add-header alt-svc \'h3=":443"; ma=3600\'
	#http-after-response add-header alt-svc \'h3=":443"; ma=3600\'

	# Remove server and x-powered-by headers
	#http-after-response del-header server
	#http-after-response del-header x-powered-by

	# Redirect to https scheme when unsecure and not acme challenge
	http-request redirect scheme https code 302 unless { ssl_fc } || acme_challenge

	# Check if denied path
	#XXX: use ,url_dec like in https://serverfault.com/questions/754752/block-specific-url-in-haproxy-url-encoding
	#acl denied_path path_reg ^/(\.env|login|admin/|wp-login\.php|\.git/config)$
	# Deny access on denied path
	#http-request deny if denied_path

	# Check if protected path
	#acl protected_path path_reg ^/(contact|register)$
	# Deny access on protected path
	#http-request deny deny_status 503 if protected_path { method \'POST\' } { req.ver \'1.0\' }

	# Store origin variable as txn
	http-request set-var(txn.origin) req.hdr(Origin)
	# Store host variable as txn
	http-request set-var(txn.host) req.hdr(Host),field(1,:),lower
	# Store proto variable as txn
	http-request set-var(txn.proto) ssl_fc,iif(https,http)

	# Set forwarded proto
	http-request set-header X-Forwarded-Proto %[var(txn.proto)]
	# Set forwarded port
	http-request set-header X-Forwarded-Port %[dst_port]
	# Set forwarded for
	#http-request set-header X-Forwarded-For %[src]
	# Set forwarded by
	http-request set-header X-Forwarded-By %[dst]

	# Set forwarded
	#http-request set-header Forwarded by=%[dst]:%[dst_port];for=%[src]:%[src_port];host=%[var(txn.host)];proto=%[var(txn.proto)]
	http-request set-header Forwarded by=%[dst]:%[dst_port];for=%[src]:%[src_port];proto=%[var(txn.proto)]

	# Check if host is cdn.example.com
	acl cdn var(txn.host) -m str cdn.example.com
	# Check if cdn css path
	acl cdn_css path_beg /css
	# Check if cdn js path
	acl cdn_js path_beg /js
	# Check if haproxy status path
	acl haproxy_status path_beg /haproxy-status
	# Check if debug path
	acl debug path_beg /debug

	# Send to css backend if path start with /css
	use_backend http_css if cdn cdn_css
	# Send to js backend if path start with /js
	use_backend http_js if cdn cdn_js
	# Send to status backend if path start with /haproxy-status
	use_backend http_status if haproxy_status
	# Send to debug backend if path start with /debug
	use_backend http_debug if debug

	# Send to https backend
	use_backend https_default if { ssl_fc }

	# Send to default backend
	default_backend http_default


# Debug http backend
backend http_debug
	# Check if trusted
	acl trusted src 127.0.0.0/8 ::1
	# Allow access from trusted only
	http-request deny unless trusted
	# Server without ssl or check
	server debug 127.0.0.1:8090 no-ssl verify none


# Default http backend
backend http_default
	# Enable check
	option httpchk
	# User server default
	http-check connect default
	# Send HEAD on / with protocol HTTP/1.1 for host example.com
	http-check send meth HEAD uri / ver HTTP/1.1 hdr Host example.com
	# Expect return code between 200 and 399
	http-check expect status 200-399

	# Insert header X-Server: apache
	#http-response add-header X-Server apache

	# Set compression algorithm
	#compression algo gzip
	# Enable compression for html, plain and css text types
	#compression type text/html text/plain text/css

	# Server with ssl and check without certificate verification
	server apache 127.0.0.1:80 no-ssl verify none check #cookie apache


# Default https backend
backend https_default
	# Enable check
	option httpchk
	# User server default
	http-check connect default
	# Send HEAD on / with protocol HTTP/1.1 for host example.com
	http-check send meth HEAD uri / ver HTTP/1.1 hdr Host example.com
	# Expect return code between 200 and 399
	http-check expect status 200-399

	# Insert header X-Server: apache
	#http-response add-header X-Server apache

	# Force HSTS for 5 minutes on domain and all subdomains
	#http-response set-header Strict-Transport-Security max-age=300#;\ includeSubDomains#;\ preload

	# Set compression algorithm
	#compression algo gzip
	# Enable compression for html, plain and css text types
	#compression type text/html text/plain text/css

	# Server with ssl and check without certificate verification
	server apache 127.0.0.1:443 ssl verify none check #cookie apache


# Css http backend
backend http_css
	# Enable check
	option httpchk
	# User server default
	http-check connect default
	# Send GET on /css/empty.css with protocol HTTP/1.1 for host cdn.example.com
	http-check send meth GET uri /css/empty.css ver HTTP/1.1 hdr Host cdn.example.com
	# Expect return code between 200 and 399
	http-check expect status 200-399

	# Server with check without ssl and certificate verification
	server css 127.0.0.1:80 no-ssl verify none check


# Js http backend
backend http_js
	# Enable check
	option httpchk
	# User server default
	http-check connect default
	# Send HEAD on /js/missing.js with protocol HTTP/1.1 for host cdn.example.com
	http-check send meth HEAD uri /js/missing.js ver HTTP/1.1 hdr Host cdn.example.com
	# Expect return code 404
	http-check expect status 404

	# Check if txn.origin start with https://cdn.example.com
	acl cdn_origin var(txn.origin) -m beg https://cdn.example.com
	# Send origin as ACAO
	http-response set-header Access-Control-Allow-Origin %[var(txn.origin)] if cdn_origin
	# Set ACMA for one day
	http-response set-header Access-Control-Max-Age 86400 if cdn_origin

	# Server with check without ssl and certificate verification
	server js 127.0.0.1:80 no-ssl verify none check


# Status user list
userlist status
	# Add user admin
	user admin insecure-password ADMINPASSWORD
	# Add user operator
	user operator insecure-password OPERATORPASSWORD
	# Assign admin in admin group
	group admin users admin
	# Assign operator and admin in operator group
	group operator users operator,admin


# Status http backend
backend http_status
	# Add operator acl
	acl is_operator http_auth(status)
	# Add admin acl
	acl is_admin http_auth_group(status) admin
	# Check if trusted
	acl trusted src 127.0.0.0/8 ::1
	# Enable stats
	stats enable
	# Set stats hook on /haproxy-status
	stats uri /haproxy-status
	# Set refresh time
	stats refresh 10s
	# Display legends
	stats show-legends
	# Display node
	stats show-node
	# Allow access from trusted or authentified operator only
	#stats http-request auth unless trusted or is_operator
	stats http-request auth unless trusted
	# Activate admin interface from trusted or authentified admin only
	#stats admin if is_admin
```

Espérons que cet article vous a été utile.'
					]
				]
			],
		];

		//Create 3 articles
		foreach($articleTree as $i => $data) {
			$article = new Article($users[$data['mail']]);
			foreach($data['keywords'] as $keyword) {
				$article->addKeyword($keywords[$keyword]);
			}
			$manager->persist($article);
			//Flush to get the id
			$manager->flush();
			$articles[$i] = $article;
			foreach($data['translations'] as $locale => $translation) {
				$articleTranslation = new ArticleTranslation($articles[$i], $locale, $translation['body'], $translation['description'], $this->slugger->slug($translation['title']), $translation['title']);
				$manager->persist($articleTranslation);
			}
			unset($article);
		}

		//Flush to get the ids
		$manager->flush();
	}
}
