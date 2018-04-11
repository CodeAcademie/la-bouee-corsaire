<?php

	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints as Assert;

	/**
	 * Registred address
	 *
	 * @ORM\Entity
	 * @ORM\Table(name="bouee_address")
	 */
	class Address {

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
		 * Physical address
		 *
		 * @ORM\Column(type="text", length=255)
		 *
		 * @Assert\NotBlank(message="Veuillez entrer votre adresse.", groups={"Registration", "Profile"})
		 *
		 * @Assert\Length(
		 * 	min=3,
		 * 	max=255,
		 * 	minMessage="L’adresse est trop courte.",
		 * 	maxMessage="L’adresse est trop longue.",
		 * 	groups={"Registration", "Profile"}
		 * )
		 *
		 * @var    string
		 * @access protected
		 */
		protected $address;

		/**
		 * Region
		 *
		 * @ORM\Column(type="string", length=100)
		 *
		 * @Assert\NotBlank(message="Veuillez entrer votre région.", groups={"Registration", "Profile"})
		 *
		 * @Assert\Length(
		 * 	min=3,
		 * 	max=100,
		 * 	minMessage="La région est trop courte.",
		 * 	maxMessage="La région est trop longue.",
		 * 	groups={"Registration", "Profile"}
		 * )
		 *
		 * @var    string
		 * @access protected
		 */
		protected $region;

		/**
		 * Zip Code
		 *
		 * @ORM\Column(type="string", length=5)
		 *
		 * @Assert\NotBlank(message="Veuillez entrer votre code postal.", groups={"Registration", "Profile"})
		 *
		 * @Assert\Length(
		 * 	min=5,
		 * 	max=5,
		 * 	minMessage="Le code postal est trop court.",
		 * 	maxMessage="Le code postal est trop long.",
		 * 	groups={"Registration", "Profile"}
		 * )
		 *
		 * @var    string
		 * @access protected
		 */
		protected $zip_code;

		/**
		 * City
		 *
		 * @ORM\Column(type="string", length=100)
		 *
		 * @Assert\NotBlank(message="Veuillez entrer votre ville.", groups={"Registration", "Profile"})
		 *
		 * @Assert\Length(
		 * 	min=3,
		 * 	max=100,
		 * 	minMessage="La ville est trop courte.",
		 * 	maxMessage="La ville est trop longue.",
		 * 	groups={"Registration", "Profile"}
		 * )
		 *
		 * @var    string
		 * @access protected
		 */
		protected $city;

		/**
		 * Latitude
		 *
		 * @ORM\Column(type="float")
		 *
		 * @Assert\NotBlank(message="Veuillez entrer la latitude.", groups={"Registration", "Profile"})
		 *
		 * @var    float
		 * @access protected
		 */
		protected $latitude;

		/**
		 * Longitude
		 *
		 * @ORM\Column(type="float")
		 *
		 * @Assert\NotBlank(message="Veuillez entrer la longitude.", groups={"Registration", "Profile"})
		 *
		 * @var    float
		 * @access protected
		 */
		protected $longitude;

		/**
		 * Return id
		 *
		 * @return int
		 */
		public function getId() { return $this->id; }

		/**
		 * Return address
		 *
		 * @return string
		 */
		public function getAddress() { return $this->address; }

		/**
		 * Return region
		 *
		 * @return string
		 */
		public function getRegion() { return $this->region; }

		/**
		 * Return zip code
		 *
		 * @return string
		 */
		public function getZipCode() { return $this->zip_code; }

		/**
		 * Return city
		 *
		 * @return string
		 */
		public function getCity() { return $this->city; }

		/**
		 * Return latitude
		 *
		 * @return float
		 */
		public function getLatitude() { return $this->latitude; }

		/**
		 * Return longitude
		 *
		 * @return float
		 */
		public function getLongitude() { return $this->longitude; }

		/**
		 * Return full address : address zip_code city
		 *
		 * @return string
		 */
		public function getFullAddress() {
			$adr = $this->getAddress().' '
					.$this->getZipCode().' '
					.$this->getCity();

			return $adr;
		}

		/**
		 * Set address
		 *
		 * @param string $address
		 *
		 * @return User
		 */
		public function setAddress($address) {
			$address = (string) $address;

			$length = strlen($address);
			if ($length >= 3 && $length <= 255) {
				$this->address = $address;
			}

			return $this;
		}

		/**
		 * Set Zip code
		 *
		 * @param string $zip_code
		 *
		 * @return User
		 */
		public function setZipCode($zip_code) {
			$zip_code = (string) $zip_code;
			$regex = preg_match( '/^[0-9][0-9]{4}$/', $zip_code);
				 if ($regex == true){
					$this->zip_code = $zip_code;
				 }
			return $this;
		}

		/**
		 * Set region
		 *
		 * @param string $region
		 *
		 * @return User
		 */
		public function setRegion($region) {
			$region = (string) $region;

			$length = strlen($region);
			if ($length >= 3 && $length <= 100) {
				$this->region = $region;
			}

			return $this;
		}

		/**
		 * Set city
		 *
		 * @param string $city
		 *
		 * @return User
		 */
		public function setCity($city) {
			$city = (string) $city;

			$length = strlen($city);
			if ($length >= 3 && $length <= 100) {
				$this->city = $city;
			}

			return $this;
		}

		/**
		 * Set latitude
		 *
		 * @param float $latitude
		 *
		 * @return User
		 */
		public function setLatitude($latitude) {
			$latitude = (float) $latitude;

			if ($latitude >= -90 && $latitude <= 90) {
				$this->latitude = $latitude;
			}

			return $this;
		}

		/**
		 * Set longitude
		 *
		 * @param float $longitude
		 *
		 * @return User
		 */
		public function setLongitude($longitude) {
			$longitude = (float) $longitude;

			if ($longitude >= -180 && $longitude <= 180) {
				$this->longitude = $longitude;
			}

			return $this;
		}

	}