
<?php
class Product
{
	public ?int $id;
	public string $name;
	public float $price;

	public function __construct(string $name, float $price, ?int $id = null)
	{
		$this->name = $name;
		$this->price = $price;
		$this->id = $id;
	}
}
?>