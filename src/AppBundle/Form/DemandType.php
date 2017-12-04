<?php

	namespace AppBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\HiddenType;


	/**
	 * Form used to generate and edit Tasks
	 */
	class DemandType extends AbstractType {

		/**
		 * Associated class
		 *
		 * @var string
		 */
		private $class;

		/**
		 * Contruct, set the associated class
		 *
		 * @param string $class
		 */
		public function __construct($class = 'AppBundle\Entity\Task') {
			$this->class = $class;
		}

		/**
		 * Builds the form.
		 *
		 * This method is called for each type in the hierarchy starting from the
		 * top most type. Type extensions can further modify the form.
		 *
		 * @param FormBuilderInterface $builder The form builder
		 * @param array                $options The options
		 */
		public function buildForm(FormBuilderInterface $builder, array $options) {
			$builder
				->add('title', null, [
					'translation_domain' => false,
					'label' => 'Description',
				])
				->add('location', null, [
					'translation_domain' => false,
					'label' => 'Lieu',
				])
				->add('level', null, [
					'translation_domain' => false,
					'label' => 'Niveau d’expertise',
				])
				->add('description', null, [
					'translation_domain' => false,
					'label' => 'Informations complémentaires',
				])
				->add('is_service', HiddenType::class, [
					'data' => 1
				]);
		}

		/**
		 * Configures the options for this type.
		 *
		 * @param OptionsResolver $resolver The resolver for the options
		 */
		public function configureOptions(OptionsResolver $resolver) {
			$resolver->setDefaults([
				'data_class' => $this->class,
			]);
		}

	}

?>
