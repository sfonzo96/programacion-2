?php
	class User {
		private $age;
		
		public function __construct($age) {
			$this->age = $age;
		}

		public function getAge() {
			return $this->age;
		}

		public function setAge($age) {
			if (!($age > 0)) {
				echo "Age should be positive!";
				exit(1);
			}

			$this->age = $age;
		}
	}

	$user = new User(25);
	echo "Age:\t{$user->getAge()}\n";
	$user->setAge(30);
	echo "Age:\t{$user->getAge()}\n";
?>
