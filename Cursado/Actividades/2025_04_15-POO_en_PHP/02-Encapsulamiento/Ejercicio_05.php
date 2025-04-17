<?php
	class Student {
		private $grades = [];
		
		public function __construct($grades) {
			$this->grades = $grades;
		}

		public function getGradesAverage() {
			$gradesSum = array_sum($this->grades);
			
			return $gradesSum / count($this->grades);
		}

		public function getGrades() {
			return $this->grades;
		}

		public function addGrade($grade) {
			if (!($grade <= 10 && $grade >= 0)) {
				echo "Grade should be within 1 and 10, both included!\n";
				// exit(1);
				return;
			}

			array_push($this->grades, $grade);
		}
	}

	$student = new Student([]);
	$student->addGrade(10);
	$student->addGrade(8);
	$student->addGrade(9);
	$student->addGrade(10);
	echo "Students grades:\n";
	foreach ($student->getGrades() as $grade) {
		echo "\t{$grade}\n";
	}
	echo "Grades average:\t{$student->getGradesAverage()}\n";
?>
