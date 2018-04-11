<?php

	namespace AppBundle\Controller;

	use AppBundle\Entity\User;	
	use FOS\UserBundle\Event\FormEvent;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

	/**
	 * Generic controller
	 */
	abstract class Controller extends BaseController {
		/**
		 * Return the current User
		 *
		 * @return User
		 */
		protected function getAuthenticatedUser() {
			if (
				!$this
					->get('security.authorization_checker')
					->isGranted('IS_AUTHENTICATED_FULLY')
			) {
				throw $this->createAccessDeniedException();
			}
			return $this->getUser();
		}

		/**
		 * Return the entity instance identified by the given class name and ID
		 *
		 * @param string $class
		 * @param int    $id
		 * @param bool   $enabled_only
		 *
		 * @return Entity
		 */
		protected function getById($class, $id, $enabled_only = true) {
			$entity = $this
				->getDoctrine()
				->getRepository('AppBundle:'.$class)
				->find($id);

			if (!$entity) {
				throw $this->createNotFoundException(
					'No '.$class.' found for id '.$id
				);
			}

			if ($enabled_only) {
				$this->checkEnabled($class, $entity);
			}

			return $entity;
		}

		/**
		 * Check that the given entity is not disabled
		 *
		 * @param string $class
		 * @param Entity $entity
		 */
		protected function checkEnabled($class, $entity) {
			if ($entity->isDisabled()) {
				throw $this->createAccessDeniedException(
					$class.' with id '.$entity->getId().' has been disabled.'
				);
			}
		}

	}

?>
