<?php

	namespace AppBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\HiddenType;
	use Symfony\Component\Form\Extension\Core\Type\EmailType;

	/**
	 * Base used by all forms related to Users creation/edition
	 */
	abstract class UserType extends AbstractType {

		/**
		 * Associated class
		 *
		 * @var string
		 */
		protected $class;

		/**
		 * Contruct, set the associated class
		 *
		 * @param string $class The User class name
		 */
		public function __construct($class = 'AppBundle\Entity\User') {
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
				->add('name', null, [
					'translation_domain' => false,
					'label' => 'Prénom',
				])
				->add('surname', null, [
					'translation_domain' => false,
					'label' => 'Nom',
				])
				->add('email', EmailType::class,[
					'translation_domain' => 'FOSUserBundle',
					'label' => 'form.email',
				])
				->add('sponsor', null, [
					'attr' => ['readonly' => true],
					'translation_domain' => false,
					'label' => 'Parrain',
				])
				->add('adress', null, [
					'translation_domain' => false,
					'label' => 'Adresse',
				])
				->add('region', null, [
					'attr' => ['readonly' => true],
					'translation_domain' => false,
					'label' => 'Région',
				])
				->add('zip_code', null, [
					'attr' => ['readonly' => true],
					'translation_domain' => false,
					'label' => 'Code Postal',
					])
				->add('city', null, [
					'attr' => ['readonly'=> true],
					'translation_domain' => false,
					'label' => 'Ville',
				])
				->add('phone', null, [
					'translation_domain' => false,
					'label' => 'Téléphone',
				])
				->add('latitude', HiddenType::class, [
					'translation_domain' => false,
					'label' => 'Latitude',
				])
				->add('longitude', HiddenType::class, [
					'translation_domain' => false,
					'label' => 'Longitude',
				]);
			}

	}

?>
