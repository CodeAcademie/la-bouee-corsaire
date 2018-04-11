<?php

	namespace AppBundle\Form;

	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
	use AppBundle\Form\UserType;

	/**
	 * Form used for User creation
	 */
	class RegistrationType extends UserType {

		/**
		 * {@inheritdoc}
		 */
		public function buildForm(FormBuilderInterface $builder, array $options) {
			parent::buildForm($builder, $options);
			$builder
				->remove('username')
				->add('email', null, array(
					'translation_domain' => 'FOSUserBundle',
					'label' => 'form.email',
				))
				->add('plainPassword', RepeatedType::class, array(
					'type' => PasswordType::class,
					'options' => array('translation_domain' => 'FOSUserBundle'),
					'first_options' => array('label' => 'form.password'),
					'second_options' => array('label' => 'form.password_confirmation'),
					'invalid_message' => 'fos_user.password.mismatch',
				));
		}

		/**
		 * Configures the options for this type.
		 *
		 * @param OptionsResolver $resolver The resolver for the options
		 */
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults(array(
				'data_class' => $this->class,
				'csrf_token_id' => 'registration',
			));
		}

	}

?>
