<?php

namespace Rapsys\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType {
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		return $builder->add('name', TextType::class, array('attr' => array('placeholder' => 'Your name'), 'constraints' => array(new NotBlank(array("message" => "Please provide your name")))))
			->add('subject', TextType::class, array('attr' => array('placeholder' => 'Subject'), 'constraints' => array(new NotBlank(array("message" => "Please give a Subject")))))
			->add('email', EmailType::class, array('attr' => array('placeholder' => 'Your email address'), 'constraints' => array(new NotBlank(array("message" => "Please provide a valid email")),	new Email(array("message" => "Your email doesn't seems to be valid")))))
			->add('message', TextareaType::class, array('attr' => array('placeholder' => 'Your message here', 'cols' => 50, 'rows' => 15), 'constraints' => array(new NotBlank(array("message" => "Please provide a message here")))))
			->add('submit', SubmitType::class, array('label' => 'Send', 'attr' => array('class' => 'submit')));
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array('error_bubbling' => true));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'contact_form';
	}
}
