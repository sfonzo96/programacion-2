<?php
	class Student {
		public $name;
		public $age;
		public $enrollment;

		public function __construct($name, $age, $enrollment) {
			$this->name = $name;
			$this->age  = $age;
			$this->enrollment = $enrollment;
		}

		public function showData() {
			echo "Student's data:\n\tName: {$this->name}\n\tAge: {$this->age}\n\tEnrollment: {$this->enrollment}\n";
		}
	}

	$student = new Student("Santiago Fonzo", 28, "F-0001");
	$student->showData();
?>
