<?php declare(strict_types=1);

/*
 * This file is part of the Rapsys BlogBundle package.
 *
 * (c) Raphaël Gertz <symfony@rapsys.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rapsys\BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use Rapsys\BlogBundle\RapsysBlogBundle;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface {
	/**
	 * {@inheritdoc}
	 */
	public function getConfigTreeBuilder(): TreeBuilder {
		$treeBuilder = new TreeBuilder($alias = RapsysBlogBundle::getAlias());

		// Here you should define the parameters that are allowed to
		// configure your bundle. See the documentation linked above for
		// more information on that topic.
		//Set defaults
		$defaults = [
			'contact' => [
				'address' => 'john.doe@example.com',
				'name' => 'John Doe'
			],
			'copy' => [
				'by' => 'Rapsys',
				'link' => 'https://example.com',
				'long' => 'All rights reserved',
				'short' => 'Copyright 2019-2023',
				'title' => 'By Raphaël'
			],
			'donate' => '',
			'facebook' => [
				'apps' => [],
				'height' => 630,
				'width' => 1200
			],
			'icon' => [
				'ico' => '@RapsysBlog/ico/icon.ico',
				//The png icon array
				//XXX: see https://www.emergeinteractive.com/insights/detail/the-essentials-of-favicons/
				//XXX: see https://caniuse.com/#feat=link-icon-svg
				'png' => [
					//Default
					256 => '@RapsysBlog/png/icon.256.png',

					//For google
					//Chrome for Android home screen icon
					196 => '@RapsysBlog/png/icon.196.png',
					//Google Developer Web App Manifest Recommendation
					192 => '@RapsysBlog/png/icon.192.png',
					//Chrome Web Store icon
					128 => '@RapsysBlog/png/icon.128.png',

					//Fallback
					32 => '@RapsysBlog/png/icon.32.png',

					//For apple
					//XXX: old obsolete format: [57, 72, 76, 114, 120, 144]
					//XXX: see https://webhint.io/docs/user-guide/hints/hint-apple-touch-icons/
					//XXX: see https://developer.apple.com/library/archive/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html
					//iPhone Retina
					180 => '@RapsysBlog/png/icon.180.png',
					//iPad Retina touch icon
					167 => '@RapsysBlog/png/icon.167.png',
					//iPad touch icon
					152 => '@RapsysBlog/png/icon.152.png',
					//iOS7
					120 => '@RapsysBlog/png/icon.120.png',

					//For windows
					//XXX: see https://docs.microsoft.com/en-us/previous-versions/windows/internet-explorer/ie-developer/platform-apis/dn255024(v=vs.85)
					310 => '@RapsysBlog/png/icon.310.png',
					150 => '@RapsysBlog/png/icon.150.png',
					70 => '@RapsysBlog/png/icon.70.png'
				],
				'svg' => '@RapsysBlog/svg/icon.svg'
			],
			//XXX: revert to underscore because of that shit:
			//XXX: see https://symfony.com/doc/current/components/config/definition.html#normalization
			//XXX: see https://github.com/symfony/symfony/issues/7405
			//TODO: copy to '%rapsys_user.languages%',
			'languages' => [
				'en_gb' => 'English'
			],
			//TODO: copy to '%kernel.default_locale%'
			'locale' => 'en_gb',
			//TODO: copy to '%kernel.translator.fallbacks%'
			'locales' => [ 'en_gb' ],
			'logo' => [
				'png' => '@RapsysBlog/png/logo.png',
				'svg' => '@RapsysBlog/svg/logo.svg',
				'alt' => 'John Doe\'s blog logo'
			],
			'path' => is_link(($prefix = is_dir('public') ? './public/' : './').($link = 'bundles/'.str_replace('_', '', $alias))) && is_dir(realpath($prefix.$link)) || is_dir($prefix.$link) ? $link : dirname(__DIR__).'/Resources/public',
			'root' => 'rapsys_blog',
			'title' => 'John Doe\'s blog',
		];

		//Here we define the parameters that are allowed to configure the bundle.
		//TODO: see https://github.com/symfony/symfony/blob/master/src/Symfony/Bundle/FrameworkBundle/DependencyInjection/Configuration.php for default value and description
		//TODO: see http://symfony.com/doc/current/components/config/definition.html
		//XXX: use bin/console config:dump-reference to dump class infos

		//Here we define the parameters that are allowed to configure the bundle.
		$treeBuilder
			//Parameters
			->getRootNode()
				->addDefaultsIfNotSet()
				->children()
					->arrayNode('contact')
						->addDefaultsIfNotSet()
						->children()
							->scalarNode('address')->cannotBeEmpty()->defaultValue($defaults['contact']['address'])->end()
							->scalarNode('name')->cannotBeEmpty()->defaultValue($defaults['contact']['name'])->end()
						->end()
					->end()
					->arrayNode('copy')
						->addDefaultsIfNotSet()
						->children()
							->scalarNode('by')->defaultValue($defaults['copy']['by'])->end()
							->scalarNode('link')->defaultValue($defaults['copy']['link'])->end()
							->scalarNode('long')->defaultValue($defaults['copy']['long'])->end()
							->scalarNode('short')->defaultValue($defaults['copy']['short'])->end()
							->scalarNode('title')->defaultValue($defaults['copy']['title'])->end()
						->end()
					->end()
					->scalarNode('donate')->cannotBeEmpty()->defaultValue($defaults['donate'])->end()
					//XXX: facebook required ???
					//@see https://symfony.com/doc/current/components/config/definition.html
					//->beforeNormalization()->castToArray()->end()
					->arrayNode('facebook')
						->addDefaultsIfNotSet()
						->children()
							->arrayNode('apps')
								->treatNullLike([])
								->defaultValue($defaults['facebook']['apps'])
								->scalarPrototype()->end()
							->end()
							->integerNode('height')->min(0)->defaultValue($defaults['facebook']['height'])->end()
							->integerNode('width')->min(0)->defaultValue($defaults['facebook']['width'])->end()
						->end()
					->end()
					->arrayNode('icon')
						->addDefaultsIfNotSet()
						->children()
							->scalarNode('ico')->defaultValue($defaults['icon']['ico'])->end()
							->arrayNode('png')
								->treatNullLike([])
								->defaultValue($defaults['icon']['png'])
								->scalarPrototype()->end()
							->end()
							->scalarNode('svg')->defaultValue($defaults['icon']['svg'])->end()
						->end()
					->end()
					#TODO: see if we can't prevent key normalisation with ->normalizeKeys(false)
					->variableNode('languages')
						->treatNullLike([])
						->defaultValue($defaults['languages'])
					->end()
					->scalarNode('locale')->cannotBeEmpty()->defaultValue($defaults['locale'])->end()
					#TODO: see if we can't prevent key normalisation with ->normalizeKeys(false)
					->variableNode('locales')
						->treatNullLike([])
						->defaultValue($defaults['locales'])
					->end()
					->arrayNode('logo')
						->addDefaultsIfNotSet()
						->children()
							->scalarNode('png')->defaultValue($defaults['logo']['png'])->end()
							->scalarNode('svg')->defaultValue($defaults['logo']['svg'])->end()
							->scalarNode('alt')->defaultValue($defaults['logo']['alt'])->end()
						->end()
					->end()
					->scalarNode('path')->defaultValue($defaults['path'])->end()
					->scalarNode('root')->cannotBeEmpty()->defaultValue($defaults['root'])->end()
					->scalarNode('title')->cannotBeEmpty()->defaultValue($defaults['title'])->end()
				->end()
			->end();

		return $treeBuilder;
	}
}
