<?php

	namespace AppBundle\Controller;

	use AppBundle\Entity\Task;
	use AppBundle\Entity\User;
	use AppBundle\Entity\StatusTrait;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Psr\Log\LoggerInterface;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;

	/**
	 * Task-related operations
	 *
	 * @Route("/task")
	 */
	class TaskController extends Controller {
		/**
		 * Check that the given Task is owned by the current User
		 *
		 * @param Task $task
		 */
		protected function checkOwnership(Task $task) {
			if ($task->getUser() !== $this->getUser()) {
				throw $this->createAccessDeniedException(
					'You are not allowed to edit the Task with id '.$task->getId()
				);
			}
		}

		/**
		 * Save Task
		 *
		 * @param Task $task
		 *
		 * @return RedirectResponse
		 */
		protected function saveTask($task) {
			// Set Task creation date to now if not already set
			if ($task->getDate() === null) {
				$task->setDate(new \DateTime());
			}

			// Set Task owner to current User if not already set
			if ($task->getUser() === null) {
				$task->setUser($this->getUser());
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($task);
			$em->flush();

			return $this->redirectToRoute('task_show', [
				'id' => $task->getId()
			]);
		}

		/**
		 * Show details of the Task identified by the given ID
		 *
		 * @Route("/show/{id}", name="task_show")
		 *
		 * @param Request $request
		 * @param int     $id
		 *
		 * @return Response
		 */
		public function showAction(Request $request, $id) {
			$task = $this->getById('Task', $id);

			return $this->render('task/show.html.twig', [
				'task' => $task,
				'user' => $this->getUser(),
			]);

		}

		/**
		 * Show a form allowing creation of a new Task owned by the current User
		 *
		 * @Route("/new", name="task_new")
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function newAction(Request $request) {
			$user = $this->getAuthenticatedUser();

			$formFactory = $this->get('form.factory');

			$option['addresses'] = $user->getListAddresses();

			//$option['addresses'] = [];
			$task = new Task;
			$form = $formFactory->createNamed(
				'new_task',
				'AppBundle\Form\TaskType',
				$task,
				$option
			);

			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				return $this->saveTask($form->getData());
			}

			return $this->render('task/new.html.twig', [
				'form' => $form->createView(),
				'task' => $task,
			]);
		}


		/**
		 * Show a form allowing creation of a new Demand owned by the current User
		 *
		 * @Route("/new/demand", name="task_new_demand")
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function newDemand(Request $request) {

			$user = $this->getAuthenticatedUser();

			$formFactory = $this->get('form.factory');

			$option['addresses'] = $user->getListAddresses();

			$task = new Task;
			$form = $formFactory->createNamed(
				'new_task',
				'AppBundle\Form\DemandType',
				$task,
				$option
			);

			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				return $this->saveTask($form->getData());
			}

			return $this->render('task/new-demand.html.twig', [
				'form' => $form->createView(),
				'task' => $task,
			]);
		}

		/**
		 * Show full list of Tasks
		 *
		 * @Route("/list", name="task_list")
		 *
		 * @return Response
		 */
		public function listAction()
		{
			$tasks = $this
				->getDoctrine()
				->getRepository('AppBundle:Task')
				->findBy(['enabled' => true],
					['date' => 'DESC']
				);

			return $this->render('task/list.html.twig', [
				'tasks' => $tasks,
				'user' => $this->getUser(),
			]);
		}

		/**
		 * Search From nav
		 *
		 * @Route("/search", name="search_task_nav")
		 *
		 */

		public function navSearch(Request $request)
		{
			$search = $request->request->get('search');
			$em = $this->getDoctrine()->getManager();
			$repository = $em->getRepository('AppBundle\Entity\Task');
			$query = $repository->createQueryBuilder('p')
				->where('p.title LIKE :word')
				->orWhere('p.description LIKE :word')
				->andWhere('p.enabled = :enabled')
				->setParameter('word', '%'.$search.'%')
				->setParameter('enabled', 1)
				->getQuery();
			$tasks = $query->getResult();

			return $this->render('task/list.html.twig', [
				'tasks' => $tasks,
				'user' => $this->getUser(),
			]);
		}

		/**
		 * Show list of Tasks with range limitation
		 *
		 * @Route("/list/{range}", name="task_list_range")
		 *
		 * @return Response
		 */
		public function listRangeAction(Request $request, $range) {

			$user = $this->getAuthenticatedUser();
			$userLat = $user->getLatitude();
			$userLong = $user->getLongitude();

			$CIRCONF = 40075; // circonférence de la terre en mètres*
			$latDiff =  360 * $range / $CIRCONF;
			$longDiff = 360 * $range / ($CIRCONF * cos(deg2rad($userLat)));
			$latMin = $userLat - $latDiff;
			$latMax = $userLat + $latDiff;
/*
			$repository = $this->getDoctrine()->getRepository('AppBundle:Task');
			$query = $repository->createQueryBuilder('p')
			    ->where('p.latitude > :latMin')
			    ->andWhere('p.latitude < :latMax')
			    ->andWhere('p.longitude > :longMin')
			    ->andWhere('p.longitude < :longMax')
			    ->setParameter('latMin', $latMin)
			    ->setParameter('latMax', $latMax)
			    ->setParameter('longMin', $longMin)
			    ->setParameter('longMax', $LongMax)
			    ->orderBy('p.price', 'ASC')
			    ->getQuery();

			$list = $query->getResult();

			foreach ($list as $key => $task) {
				if($task['isService'])
			}

*/

			$tasks = $this
				->getDoctrine()
				->getRepository('AppBundle:Task')
				->findBy(
					['enabled' => true],
					['date' => 'DESC']
				);
			$demands = $this
				->getDoctrine()
				->getRepository('AppBundle:Task')
				->findBy(
					['enabled' => true, 'isService' => true],
					['date' => 'DESC']
				);

			return $this->render('task/list.html.twig', [
				'tasks' => $tasks,
				'demands' => $demands,
				'user' => $this->getUser(),
			]);
		}

		/**
		 * Show list of Tasks owned by current User
		 *
		 * @Route("/list-owned", name="task_list_owned")
		 *
		 * @return Response
		 */
		public function listOwnedAction() {
			$user = $this->getUser();

			$list = $this
				->getDoctrine()
				->getRepository('AppBundle:Task')
				->findBy(
					['user' => $user, 'isService' => false],
					['date' => 'DESC']
				);

			$list_enabled = [];
			$list_disabled = [];

			foreach ($list as $task) {
				if ($task->isDisabled()) {
					$list_disabled[] = $task;
				}
				else {
					$list_enabled[] = $task;
				}
			}

			$demands = $this
				->getDoctrine()
				->getRepository('AppBundle:Task')
				->findBy(
					['user' => $user, 'isService' => true],
					['date' => 'DESC']
				);

				$demands_enabled = [];
				$demands_disabled = [];

			foreach ($demands as $demand) {
				if ($demand->isDisabled()) {
					$demands_disabled[] = $demand;
				}
				else {
					$demands_enabled[] = $demand;
				}
			}

			return $this->render('task/list-owned.html.twig', [
				'tasks_enabled'  => $list_enabled,
				'tasks_disabled' => $list_disabled,
				'demands_enabled' => $demands_enabled,
				'demands_disabled' => $demands_disabled,
				'user'           => $user,
			]);
		}

		/**
		 * Show a form allowing edition of the Task identified by the given ID
		 *
		 * @Route("/edit/{id}", name="task_edit")
		 *
		 * @param Request $request
		 * @param int     $id
		 *
		 * @return Response
		 */
		public function editAction(Request $request, $id) {
			$user = $this->getAuthenticatedUser();
			$task = $this->getById('Task', $id);
			$this->checkOwnership($task);

			$option['addresses'] = $user->getListAddresses();

			$formFactory = $this->get('form.factory');
			$form = $formFactory->createNamed(
				'edit_task',
				'AppBundle\Form\TaskType',
				$task,
				$option
			);

			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				return $this->saveTask($form->getData());
			}

			return $this->render('task/edit.html.twig', [
				'form' => $form->createView(),
				'task' => $task
			]);
		}

		/**
		 * Show a form allowing duplication of the Task identified by the given ID
		 *
		 * @Route("/duplicate/{id}", name="task_duplicate")
		 *
		 * @param Request $request
		 * @param int     $id
		 *
		 * @return Response
		 */
		public function duplicateAction(Request $request, $id) {
			$user = $this->getAuthenticatedUser();
			$task = $this->getById('Task', $id, false);
			$this->checkOwnership($task);

			$option['addresses'] = $user->getListAddresses();

			$duplicated_task = $task->duplicate();
			$duplicated_task->setEnabled(true);
			$formFactory = $this->get('form.factory');
			if ($duplicated_task->getIsService() == 0){
				$form = $formFactory->createNamed(
					'new_task',
					'AppBundle\Form\TaskType',
					$duplicated_task,
					$option
				);
			} else {
				$form = $formFactory->createNamed(
					'new_task',
					'AppBundle\Form\DemandType',
					$duplicated_task,
					$option
				);
			}

			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {

				return $this->saveTask($form->getData());
			}

			return $this->render('task/duplicate.html.twig', [
				'form' => $form->createView(),
				'task' => $task,
			]);
		}

		/**
		 * Disable the Task identified by the given ID
		 *
		 * @Route("/disable/{id}", name="task_disable")
		 *
		 * @param Request $request
		 * @param int     $id
		 *
		 * @return Response
		 */
		public function disableAction(Request $request, $id) {
			$user = $this->getAuthenticatedUser();
			$task = $this->getById('Task', $id);
			$this->checkOwnership($task);

			$task->disable();
			$this->getDoctrine()->getManager()->flush();

			$this->addFlash(
				'notice',
				'La tâche ' . $task->getTitle() . ' a été désactivée.'
			);

			return $this->redirectToRoute('task_list_owned');
		}

		/**
		 * Enable the Task identified by the given ID
		 *
		 * @Route("/open/{id}", name="task_open")
		 *
		 * @param Request $request
		 * @param int     $id
		 *
		 * @return Response
		 */
		public function openAction(Request $request, $id) {
			$user = $this->getAuthenticatedUser();
			$task = $this->getById('Task', $id, false);
			$this->checkOwnership($task);

			$task->enable();
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('task_show', [
				'id' => $id
			]);
		}





	}

?>
