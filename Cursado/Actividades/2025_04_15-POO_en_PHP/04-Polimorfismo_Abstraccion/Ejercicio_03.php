<?php
	declare(strict_types=1);

	interface Printable {
		public function print(): void;
	}

	class Document implements Printable {
		public function print(): void {
			echo "Printing a document in a simple paper sheet. brrrr.";
		}
	}
		
	class Photo implements Printable {
		public function print(): void {
			echo "Printing a photo in a special paper sheet. brrrr.";
		}
	}

	function printAPrintable(Printable $printable) {
		$printable->print();
	}

	$document = new Document();
	$photo = new Photo();

	printAPrintable($document);
	echo "\n";
	printAPrintable($photo);
?>	
