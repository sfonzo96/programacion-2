<?php
	class Book {
		public $title;
		public $author;
		
		public function showBookData() {
			if (!$this->title || !$this->author) {
				echo "Title or author is undefined.";
				exit(1);
			}
		
			echo "Title: {$this->title}. Author: {$this->author}\n";
		}
	}

	$book = new Book();
	$book->title = "Mastering Go";
	$book->author = "Mihalis Tsoukalos";
	
	$book->showBookData();
?>
