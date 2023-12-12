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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Asset\PackageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

use Rapsys\BlogBundle\Entity\Dance;
use Rapsys\BlogBundle\Entity\Location;
use Rapsys\BlogBundle\Entity\Slot;
use Rapsys\BlogBundle\Entity\User;
use Rapsys\BlogBundle\RapsysBlogBundle;

use Rapsys\PackBundle\Util\FacebookUtil;
use Rapsys\PackBundle\Util\ImageUtil;
use Rapsys\PackBundle\Util\SluggerUtil;

/**
 * Provides common features needed in controllers.
 *
 * {@inheritdoc}
 */
abstract class AbstractController extends BaseAbstractController implements ServiceSubscriberInterface {
	/**
	 * Config array
	 */
	protected array $config;

	/**
	 * Count integer
	 */
	protected int $count;

	/**
	 * Context array
	 */
	protected array $context;

	/**
	 * Limit integer
	 */
	protected int $limit;

	/**
	 * Locale string
	 */
	protected string $locale;

	/**
	 * Modified DateTime
	 */
	protected \DateTime $modified;

	/**
	 * Page integer
	 */
	protected int $page;

	/**
	 * Route string
	 */
	protected string $route;

	/**
	 * Route params array
	 */
	protected array $routeParams;

	/**
	 * AuthorizationCheckerInterface instance
	 */
	protected AuthorizationCheckerInterface $checker;

	/**
	 * AccessDecisionManagerInterface instance
	 */
	protected AccessDecisionManagerInterface $decision;

	/**
	 * ManagerRegistry instance
	 */
	protected ManagerRegistry $doctrine;

	/**
	 * FacebookUtil instance
	 */
	protected FacebookUtil $facebook;

	/**
	 * FormFactoryInterface instance
	 */
	protected FormFactoryInterface $factory;

	/**
	 * Image util instance
	 */
	protected ImageUtil $image;

	/**
	 * MailerInterface instance
	 */
	protected MailerInterface $mailer;

	/**
	 * EntityManagerInterface instance
	 */
	protected EntityManagerInterface $manager;

	/**
	 * PackageInterface instance
	 */
	protected PackageInterface $package;

	/**
	 * Request instance
	 */
	protected Request $request;

	/**
	 * Router instance
	 */
	protected RouterInterface $router;

	/**
	 * Slugger util instance
	 */
	protected SluggerUtil $slugger;

	/**
	 * Security instance
	 */
	protected Security $security;

	/**
	 * RequestStack instance
	 */
	protected RequestStack $stack;

	/**
	 * Translator instance
	 */
	protected TranslatorInterface $translator;

	/**
	 * Twig\Environment instance
	 */
	protected Environment $twig;

	/**
	 * Abstract constructor
	 *
	 * @param AuthorizationCheckerInterface $checker The container instance
	 * @param ContainerInterface $container The container instance
	 * @param AccessDecisionManagerInterface $decision The decision instance
	 * @param ManagerRegistry $doctrine The doctrine instance
	 * @param FacebookUtil $facebook The facebook instance
	 * @param FormFactoryInterface $factory The factory instance
	 * @param ImageUtil $image The image instance
	 * @param MailerInterface $mailer The mailer instance
	 * @param EntityManagerInterface $manager The manager instance
	 * @param PackageInterface $package The package instance
	 * @param RouterInterface $router The router instance
	 * @param SluggerUtil $slugger The slugger instance
	 * @param Security $security The security instance
	 * @param RequestStack $stack The stack instance
	 * @param TranslatorInterface $translator The translator instance
	 * @param Environment $twig The twig environment instance
	 * @param integer $limit The page limit
	 *
	 * @TODO move all that stuff to setSlugger('@slugger') setters with a calls: [ setSlugger: [ '@slugger' ] ] to unbload classes ???
	 * @TODO add a calls: [ ..., prepare: ['@???'] ] that do all the logic that can't be done in constructor because various things are not available
	 */
	public function __construct(AuthorizationCheckerInterface $checker, ContainerInterface $container, AccessDecisionManagerInterface $decision, ManagerRegistry $doctrine, FacebookUtil $facebook, FormFactoryInterface $factory, ImageUtil $image, MailerInterface $mailer, EntityManagerInterface $manager, PackageInterface $package, RouterInterface $router, SluggerUtil $slugger, Security $security, RequestStack $stack, TranslatorInterface $translator, Environment $twig, int $limit = 5) {
		//Set checker
		$this->checker = $checker;

		//Retrieve config
		$this->config = $container->getParameter(RapsysBlogBundle::getAlias());

		//Set the container
		$this->container = $container;

		//Set decision
		$this->decision = $decision;

		//Set doctrine
		$this->doctrine = $doctrine;

		//Set facebook
		$this->facebook = $facebook;

		//Set factory
		$this->factory = $factory;

		//Set image
		$this->image = $image;

		//Set limit
		$this->limit = $limit;

		//Set mailer
		$this->mailer = $mailer;

		//Set manager
		$this->manager = $manager;

		//Set package
		$this->package = $package;

		//Set router
		$this->router = $router;

		//Set slugger
		$this->slugger = $slugger;

		//Set security
		$this->security = $security;

		//Set stack
		$this->stack = $stack;

		//Set translator
		$this->translator = $translator;

		//Set twig
		$this->twig = $twig;

		//Get main request
		$this->request = $this->stack->getCurrentRequest();

		//Get current locale
		$this->locale = $this->request->getLocale();

		//Set canonical
		$canonical = null;

		//Set alternates
		$alternates = [];

		//Get current page
		$this->page = (int) $this->request->query->get('page');

		//With negative page
		if ($this->page < 0) {
			$this->page = 0;
		}

		//Set route
		//TODO: default to not found route ???
		//TODO: when url not found, this attribute is not defined, how do we handle it ???
		//XXX: do we generate a dummy default route by default ???
		$this->route = $this->request->attributes->get('_route');

		//Set route params
		$this->routeParams = $this->request->attributes->get('_route_params');

		//With route and routeParams
		if ($this->route !== null && $this->routeParams !== null) {
			//Set canonical
			$canonical = $this->router->generate($this->route, $this->routeParams, UrlGeneratorInterface::ABSOLUTE_URL);

			//Set alternates
			$alternates = [];
		}

		//Set the context
		$this->context = [
			'head' => [
				'alternates' => $alternates,
				'canonical' => $canonical,
				'icon' => $this->config['icon'],
				'keywords' => null,
				'locale' => str_replace('_', '-', $this->locale),
				'logo' => [
					'png' => $this->config['logo']['png'],
					'svg' => $this->config['logo']['svg'],
					'alt' => $this->translator->trans($this->config['logo']['alt'])
				],
				'root' => $this->config['root'],
				'site' => $this->translator->trans($this->config['title']),
				'title' => null,
				'facebook' => [
						'og:type' => 'article',
						'og:site_name' => $this->translator->trans($this->config['title']),
						'og:url' => $canonical,
						//TODO: review this value
						'fb:app_id' => $this->config['facebook']['apps']
				],
				'fbimage' => [
					'texts' => [
						$this->translator->trans($this->config['title']) => [
							'font' => 'irishgrover',
							'size' => 110
						]
					]
				]
			],
			'contact' => [
				'address' => $this->config['contact']['address'],
				'name' => $this->translator->trans($this->config['contact']['name'])
			],
			'copy' => [
				'by' => $this->translator->trans($this->config['copy']['by']),
				'link' => $this->config['copy']['link'],
				'long' => $this->translator->trans($this->config['copy']['long']),
				'short' => $this->translator->trans($this->config['copy']['short']),
				'title' => $this->config['copy']['title']
			],
			'forms' => [],
			'description' => null,
			'section' => null,
			'title' => null
		];
	}

	/**
	 * Renders a view
	 *
	 * {@inheritdoc}
	 */
	protected function render(string $view, array $parameters = [], Response $response = null): Response {
		//Create response when null
		$response ??= new Response();

		//Without alternates
		if (empty($parameters['head']['alternates'])) {
			//Iterate on locales excluding current one
			foreach($this->config['locales'] as $locale) {
				//Set routeParams
				$routeParams = $this->routeParams;

				//With current locale
				if ($locale !== $this->locale) {
					//Set titles
					$titles = [];

					//Set route params locale
					$routeParams['_locale'] = $locale;

					//Unset slug
					//XXX: slug is in current locale, better use a simple redirect for invalid slug than implement a hard to get translation here
					unset($routeParams['slug']);

					//Iterate on other locales
					foreach(array_diff($this->config['locales'], [$locale]) as $other) {
						//Set other locale title
						$titles[$other] = $this->translator->trans($this->config['languages'][$locale], [], null, $other);
					}

					//Set locale locales context
					$parameters['head']['alternates'][str_replace('_', '-', $locale)] = [
						'absolute' => $this->router->generate($this->route, $routeParams, UrlGeneratorInterface::ABSOLUTE_URL),
						'relative' => $this->router->generate($this->route, $routeParams),
						'title' => implode('/', $titles),
						'translated' => $this->translator->trans($this->config['languages'][$locale], [], null, $locale)
					];

					//Add shorter locale
					if (empty($parameters['head']['alternates'][$shortCurrent = substr($locale, 0, 2)])) {
						//Set locale locales context
						$parameters['head']['alternates'][$shortCurrent] = $parameters['head']['alternates'][str_replace('_', '-', $locale)];
					}
				//Add shorter locale
				} elseif (empty($parameters['head']['alternates'][$shortCurrent = substr($locale, 0, 2)])) {
					//Set titles
					$titles = [];

					//Set route params locale
					$routeParams['_locale'] = $locale;

					//Iterate on other locales
					foreach(array_diff($this->config['locales'], [$locale]) as $other) {
						//Set other locale title
						$titles[$other] = $this->translator->trans($this->config['languages'][$locale], [], null, $other);
					}

					//Set locale locales context
					$parameters['head']['alternates'][$shortCurrent] = [
						'absolute' => $this->router->generate($this->route, $routeParams, UrlGeneratorInterface::ABSOLUTE_URL),
						'relative' => $this->router->generate($this->route, $routeParams),
						'title' => implode('/', $titles),
						'translated' => $this->translator->trans($this->config['languages'][$locale], [], null, $locale)
					];
				}
			}
		}

		//With empty head title and section
		if (empty($parameters['head']['title']) && !empty($parameters['section'])) {
			//Set head title
			$parameters['head']['title'] = implode(' - ', [$parameters['title'], $parameters['section'], $parameters['head']['site']]);
		//With empty head title
		} elseif (empty($parameters['head']['title'])) {
			//Set head title
			$parameters['head']['title'] = implode(' - ', [$parameters['title'], $parameters['head']['site']]);
		}

		//With empty head description and description
		if (empty($parameters['head']['description']) && !empty($parameters['description'])) {
			//Set head description
			$parameters['head']['description'] = $parameters['description'];
		}

		//With empty facebook title and title
		if (empty($parameters['head']['facebook']['og:title']) && !empty($parameters['title'])) {
			//Set facebook title
			$parameters['head']['facebook']['og:title'] = $parameters['title'];
		}

		//With empty facebook description and description
		if (empty($parameters['head']['facebook']['og:description']) && !empty($parameters['description'])) {
			//Set facebook description
			$parameters['head']['facebook']['og:description'] = $parameters['description'];
		}

		//With locale
		if (!empty($this->locale)) {
			//Set facebook locale
			$parameters['head']['facebook']['og:locale'] = $this->locale;

			//With alternates
			//XXX: locale change when fb_locale=xx_xx is provided is done in FacebookSubscriber
			//XXX: see https://stackoverflow.com/questions/20827882/in-open-graph-markup-whats-the-use-of-oglocalealternate-without-the-locati
			if (!empty($parameters['head']['alternates'])) {
				//Iterate on alternates
				foreach($parameters['head']['alternates'] as $lang => $alternate) {
					if (strlen($lang) == 5) {
						//Set facebook locale alternate
						$parameters['head']['facebook']['og:locale:alternate'] = str_replace('-', '_', $lang);
					}
				}
			}
		}

		//With count
		if (!empty($this->count)) {
			//With prev link
			if ($this->page > 0) {
				//Set head prev
				$parameters['head']['prev'] = $this->generateUrl($this->request->get('_route'), ['page' => $this->page - 1]+$this->request->get('_route_params'));
			}

			//With next link
			if ($this->count > ($this->page + 1) * $this->limit) {
				//Set head next
				$parameters['head']['next'] = $this->generateUrl($this->request->get('_route'), ['page' => $this->page + 1]+$this->request->get('_route_params'));
			}
		}

		//Without facebook image defined and texts
		if (empty($parameters['head']['facebook']['og:image']) && !empty($this->request) && !empty($parameters['head']['fbimage']['texts']) && !empty($this->modified)) {
			//Get facebook image
			$parameters['head']['facebook'] += $this->facebook->getImage($this->request->getPathInfo(), $parameters['head']['fbimage']['texts'], $this->modified->getTimestamp());
		}

		//Call twig render method
		$content = $this->twig->render($view, $parameters);

		//Invalidate OK response on invalid form
		if (200 === $response->getStatusCode()) {
			foreach ($parameters as $v) {
				if ($v instanceof FormInterface && $v->isSubmitted() && !$v->isValid()) {
					$response->setStatusCode(422);
					break;
				}
			}
		}

		//Store content in response
		$response->setContent($content);

		//Return response
		return $response;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see vendor/symfony/framework-bundle/Controller/AbstractController.php
	 */
	public static function getSubscribedServices(): array {
		//Return subscribed services
		return [
			'security.authorization_checker' => AuthorizationCheckerInterface::class,
			'service_container' => ContainerInterface::class,
			'rapsys_user.access_decision_manager' => AccessDecisionManagerInterface::class,
			'doctrine' => ManagerRegistry::class,
			'rapsys_pack.facebook_util' => FacebookUtil::class,
			'form.factory' => FormFactoryInterface::class,
			'rapsys_pack.image_util' => ImageUtil::class,
			'mailer.mailer' => MailerInterface::class,
			'doctrine.orm.default_entity_manager' => EntityManagerInterface::class,
			'rapsys_pack.path_package' => PackageInterface::class,
			'router' => RouterInterface::class,
			'rapsys_pack.slugger_util' => SluggerUtil::class,
			'security' => Security::class,
			'stack' => RequestStack::class,
			'translator' => TranslatorInterface::class,
			'twig' => Environment::class,
		];
	}
}
