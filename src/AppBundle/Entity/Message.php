<?php

	namespace AppBundle\Entity;

	use AppBundle\Entity\Transaction;
	use AppBundle\Entity\User;
	use Doctrine\ORM\Mapping as ORM;

	/**
	 * Messages sent between Users
	 *
	 * @ORM\Entity
	 * @ORM\Table(name="bouee_messages")
	 */
	class Message {

		use StatusTrait;

		/**
		 * ID
		 *
		 * @ORM\Column(type="integer", options={"unsigned"=true})
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		 *
		 * @var    int
		 * @access protected
		 */
		protected $id;

		/**
		 * Content
		 *
		 * @ORM\Column(type="text", length=65535)
		 *
		 * @var    string
		 * @access protected
		 */
		protected $content;

		/**
		 * Creation date
		 *
		 * @ORM\Column(type="datetimetz")
		 *
		 * @var    DateTime
		 * @access protected
		 */
		protected $date;

		/**
		 * User who sent the Message
		 *
		 * @ORM\ManyToOne(targetEntity="User")
		 *
		 * @var    User
		 * @access private
		 */
		protected $author;

		/**
		 * User who received the Message
		 *
		 * @ORM\ManyToOne(targetEntity="User")
		 *
		 * @var    User
		 * @access private
		 */
		protected $dest;

		/**
		 * Associated Transaction
		 *
		 * @ORM\ManyToOne(targetEntity="Transaction", inversedBy="messages")
		 *
		 * @var    Transaction $transaction
		 * @access private
		 */
		protected $transaction;

		/**
		 * Estimated duration of the associated Task
		 *
		 * @ORM\Column(type="float", scale=2, nullable=false, options={"unsigned"=true, "default"=0})
		 *
		 * @var    float $duration
		 * @access protected
		 */
		protected $duration;

		/**
		 * Tells whether the associated Transaction should be validated
		 *
		 * @ORM\Column(type="boolean", options={"default"=false})
		 *
		 * @var    boolean
		 * @access protected
		 */
		protected $validation = false;

		/**
		 * Return ID
		 *
		 * @return integer
		 */
		public function getId() { return $this->id; }

		/**
		 * Return content
		 *
		 * @return string
		 */
		public function getContent() { return $this->content; }

		/**
		 * Return creation date
		 *
		 * @return \DateTime
		 */
		public function getDate() { return $this->date; }

		/**
		 * Return author
		 *
		 * @return User
		 */
		public function getAuthor()
		{
			return $this->author;
		}

		/**
		 * Return recipient
		 *
		 * @return User
		 */
		public function getDest() { return $this->dest; }

		/**
		 * Return associated Transaction
		 *
		 * @return Transaction
		 */
		public function getTransaction() { return $this->transaction; }

		/**
		 * Return estimated duration for the associated Task
		 *
		 * @return float
		 */
		public function getDuration() { return $this->duration; }

		/**
		 * Return whether the associated Transaction should be validated
		 *
		 * @return boolean
		 */
		public function getValidation() { return $this->validation; }

		/**
		* Set content
		*
		* @param string $content
		*
		* @return Message
		*/
		public function setContent($content) {
			$this->content = $content;
			return $this;
		}

		/**
		* Set creation date
		*
		* @param \DateTime $date
		*
		* @return Message
		*/
		public function setDate(\DateTime $date) {
			$this->date = $date;
			return $this;
		}

		/**
		* Set author
		*
		* @param User $author
		*
		* @return Message
		*/
		public function setAuthor(User $author = null) {
			$this->author = $author;
			return $this;
		}

		/**
		* Set recipient
		*
		* @param User $dest
		*
		* @return Message
		*/
		public function setDest(User $dest = null) {
			$this->dest = $dest;
			return $this;
		}

		/**
		* Set associated Transaction
		*
		* @param Transaction $transaction
		*
		* @return Message
		*/
		public function setTransaction(Transaction $transaction = null) {
			$this->transaction = $transaction;
			return $this;
		}

		/**
		 * Set estimated duration for the associated Task
		 *
		 * @param float
		 *
		 * @return Transaction
		 */
		public function setDuration($duration) {
			$duration = (float) $duration;
			if ($duration >= 0) {
				$this->duration = $duration;
			}
			return $this;
		}

		/**
		 * Set whether the associated Transaction should be validated
		 *
		 * @param boolean
		 *
		 * @return Message
		 */
		public function setValidation($validation) {
			$this->validation = $validation;
			return $this;
		}

		/**
		 * Build a new Message instance from parameters given in an array
		 *
		 * @param array
		 *
		 * @return Message
		 */
		public static function fromArray($array) {
			$message = new static();
			foreach ($array as $key => $value) {
				$method = 'set'.ucfirst($key);
				if (method_exists($message, $method)) {
					$message->$method($value);
				}
			}
			return $message;
		}
	}

?>
