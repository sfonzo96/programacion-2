<?php
	class Book {
		public $title;
		public $author;
		
		public function showBookData() {
			if (!$this->title || !$this->author) {
				echo "Title or author is undefined.";
				return;
			}
		
			echo "Title: {$this->title}. Author: {$this->author}\n";
		}
	}

	$book = new Book();
	$book->title = "Mastering Go";
	$book->author = "Mihalis Tsoukalos";
	
	$book->showBookData();
?>
