<?php

	namespace Tests\AppBundle\Entity;

	use AppBundle\DBAL\Types\TaskLevelType;
	use AppBundle\Entity\Task;
	use AppBundle\Entity\User;

	class TaskTest extends \PHPUnit_Framework_TestCase {

		/**
		 * @return Task
		 */
		protected function getTask() {
			return $this->getMockForAbstractClass('AppBundle\Entity\Task');
		}

		/**
		 * @return User
		 */
		protected function getUser() {
			return $this->getMockForAbstractClass('AppBundle\Entity\User');
		}

		public function testTitle() {
			$task = $this->getTask();
			$this->assertNull($task->getTitle());

			$task->setTitle('Hello World!');
			$this->assertSame('Hello World!', $task->getTitle());
		}

		public function testDescription() {
			$task = $this->getTask();
			$this->assertNull($task->getDescription());

			$task->setDescription('Let’s discuss about things…');
			$this->assertSame('Let’s discuss about things…', $task->getDescription());
		}

		public function testLocation() {
			$task = $this->getTask();
			$this->assertNull($task->getLocation());

			$task->setLocation('Rennes');
			$this->assertSame('Rennes', $task->getLocation());
		}

		public function testEnabled() {
			$task = $this->getTask();
			$this->assertFalse($task->isDisabled());

			$task->disable();
			$this->assertTrue($task->isDisabled());

			$task->enable();
			$this->assertFalse($task->isDisabled());
		}

		public function testUser() {
			$task = $this->getTask();
			$this->assertNull($task->getUser());

			$user = $this->getUser();
			$task->setUser($user);
			$this->assertSame($user, $task->getUser());
		}

		public function testDate() {
			$task = $this->getTask();
			$this->assertNull($task->getDate());

			$date = new \DateTime();
			$task->setDate($date);
			$this->assertSame($date, $task->getDate());
		}

		public function testLevel() {
			$task = $this->getTask();
			$this->assertSame(TaskLevelType::NONE, $task->getLevel());

			$task->setLevel(TaskLevelType::EXPERT);
			$this->assertSame(TaskLevelType::EXPERT, $task->getLevel());
		}

		public function testIsService() {
			$task = $this->getTask();
			$this->assertFalse($task->isService());

			$task->setIsService(true);
			$this->assertTrue($task->getIsService());

			$task->setIsService(false);
			$this->assertFalse($task->getIsService());
		}

	}

?>
