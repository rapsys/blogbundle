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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

use Rapsys\PackBundle\Util\SluggerUtil;

use Rapsys\BlogBundle\Entity\Civility;
use Rapsys\BlogBundle\Entity\Group;
use Rapsys\BlogBundle\Entity\User;
use Rapsys\BlogBundle\Entity\UserTranslation;
use Rapsys\BlogBundle\Entity\Keyword;
use Rapsys\BlogBundle\Entity\KeywordTranslation;
use Rapsys\BlogBundle\Entity\Article;
use Rapsys\BlogBundle\Entity\ArticleTranslation;

#use Rapsys\AirBundle\Entity\Location;
#use Rapsys\AirBundle\Entity\Slot;

class BlogFixture extends Fixture implements ContainerAwareInterface {
	/**
	 * @var ContainerInterface
	 */
	private ContainerInterface $container;

	/**
	 * @var PasswordHasherFactory
	 */
	private PasswordHasherFactory $hasher;

	/**
	 * @var Rapsys\PackBundle\Util\SluggerUtil
	 */
	private SluggerUtil $slugger;

	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
		$this->hasher = $container->get('security.password_hasher_factory');
		$this->slugger = $container->get('rapsys_pack.slugger_util');
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
				'disabled' => false,
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
			$user = new User($userData['mail'], $userData['password'], $civilitys[$userData['civility']], $userData['forename'], $userData['surname'], $userData['active'], $userData['disabled'], $userData['pseudonym'], $userData['slug']);
			#$user->setPassword($this->hasher->hashPassword($user, $userData['password']));
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
					'description' => 'HATEOAS, abréviation d\'Hypermedia As Engine of Application State, Hypermédia en tant que moteur de l\'état d\'application, constitue une contrainte de l\'architecture d\'application REST qui la distingue de la plupart des autres architectures d\'applications réseau'
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
					'description' => 'MySQL is an open-source relational database management system, RDBMS'
				],
				'fr_fr' => [
					'title' => 'MySQL',
					'description' => 'MySQL est un système de gestion de bases de données relationnelles libre'
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
			]
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
						'title' => 'How to reliably detect PNG image transparency with PHP',
						'description' => 'I recently had to find out if a PNG has transparency using PHP.
	All the code I found didn\'t seemed to work correctly for the various kind of PNG I had to deal with.
	Here is the function I used.',
						'body' => 'I recently had to find out if a PNG has transparency using PHP.
All the code I found didn\'t seemed to work correctly for the various kind of PNG I had to deal with.

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
- <http://camendesign.com/code/uth1_is-png-32bit>
- <https://stackoverflow.com/questions/5495275/how-to-check-if-an-image-has-transparency-using-gd>

Hope this helps someone else out there.'
					],
					'fr_fr' => [
						'title' => 'Comment détecter la tranparence dans des images PNG en PHP de manière fiable',
						'description' => 'J\'ai récemment du trouver comment détecter en PHP les images PNG transparentes.
	Les codes trouvés ne semblaient pas fonctionner de manière satisfaisante pour les différents types de PNG à contrôler.
	Voici la fonction que j\'ai fini par utiliser.',
						'body' => 'J\'ai récemment du trouver comment détecter en PHP les images PNG transparentes.
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
- <http://camendesign.com/code/uth1_is-png-32bit>
- <https://stackoverflow.com/questions/5495275/how-to-check-if-an-image-has-transparency-using-gd>

En espérant que cela puisse aider quelques personnes.'
					]
				]
			],
			[
				'mail' => 'blog@rapsys.eu',
				'keywords' => ['hateoas', 'rest', 'uri', 'varnish', 'webservice'],
				'translations' => [
					'en_gb' => [
						'title' => 'Caching webservice with varnish',
						'description' => 'I recently had to find a way to cache a webservice anwsers.
	Here is the Varnish configuration fitting my needs.',
						'body' => 'I recently had to find a way to cache a webservice anwsers.

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
						'title' => 'Mise en cache de webservice avec varnish',
						'description' => 'J\'ai eu récemment à trouver comment mettre en cache les réponses d\'un webservice.
	Voici la configuration varnish qui a répondu à mes besoins.',
						'body' => 'J\'ai eu récemment à trouver comment mettre en cache les réponses d\'un webservice.

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
				'keywords' => ['amazon', 'azure', 'cidr', 'microsoft', 'mysql', 'php', 'webservice'],
				'translations' => [
					'en_gb' => [
						'title' => 'Dealing with IP range in PHP/MySQL',
						'description' => 'I recently had to deal with CIDR blocks to tighten some webservice security.
	Here is how I designed it to fulfill my needs.',
						'body' => 'I recently had to deal with CIDR blocks to tighten some webservice security.

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

Microsoft Azure Ip ranges urls:
- <https://www.microsoft.com/en-us/download/details.aspx?id=41653>
- <https://msdn.microsoft.com/library/mt757330.aspx>'
					],
					'fr_fr' => [
						'title' => 'Gestion des plages d\'IP en PHP/MySQL',
						'description' => 'J\'ai eu récemment à trouver comment restreindre l\'accès à un service en ligne à certaines plages d\'IP. Voici la solution qui a répondu à mes besoins.',
						'body' => 'J\'ai récemment du autoriser l\'accès à un service en ligne à seulement quelques plages d\'IP.

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
- <https://msdn.microsoft.com/library/mt757330.aspx>'
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
