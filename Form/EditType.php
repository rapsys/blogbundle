<?php declare(strict_types=1);

/*
 * This file is part of the Rapsys AirBundle package.
 *
 * (c) Raphaël Gertz <symfony@rapsys.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rapsys\BlogBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Rapsys\UserBundle\Form\EditType as BaseEditType;

class EditType extends BaseEditType {
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface {
		//Call parent build form
		$form = parent::buildForm($builder, $options);

		//Add extra pseudonym field
		if (!empty($options['pseudonym'])) {
			$form->add('pseudonym', TextType::class, ['attr' => ['placeholder' => 'Your pseudonym'], 'required' => false]);
		}

		//Add extra slug field
		if (!empty($options['slug'])) {
			$form->add('slug', TextType::class, ['attr' => ['placeholder' => 'Your slug'], 'required' => false]);
		}

		//Return form
		return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void {
		//Call parent configure options
		parent::configureOptions($resolver);

		//Set defaults
		$resolver->setDefaults(['pseudonym' => true, 'slug' => false]);

		//Add extra pseudonym option
		$resolver->setAllowedTypes('pseudonym', 'boolean');

		//Add extra slug option
		$resolver->setAllowedTypes('slug', 'boolean');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName(): string {
		return 'rapsys_blog_edit';
	}
}
