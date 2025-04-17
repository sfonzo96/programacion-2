<?php
	class Book {
		private $numberOfPages;

		public function __construct($numberOfPages) {
			$this->numberOfPages = $numberOfPages;
		}

		public function getNumberOfPages() {
			return $this->numberOfPages;
		}

		public function setNumberOfPages($numberOfPages) {
			if (!($numberOfPages > 0)) {
				echo "Number of pages must be positive! You entered {$numberOfPages}.\n";
				return;
			}
			
			$this->numberOfPages = $numberOfPages;
		}
	}

	$book = new Book(500);
	echo "This book has {$book->getNumberOfPages()} pages.\n";
	$book->setNumberOfPages(-50);
	$book->setNumberOfPages(750);
	echo "This book has {$book->getNumberOfPages()} pages.\n";
?>
