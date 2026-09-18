<?php
require_once '../app/bootstrap.php';

class Item {
	private string $catalog_number;
	private int $quantity;
	private string $name;
	private string $price;

	public function __construct(string $catalogNumber, int $quantity, string $name, int $price)
	{
		if ($quantity < 1) {
			throw new InvalidArgumentException('Quantity must be 1 or above.');
		}

		if (!preg_match('/^\d+$/', $catalogNumber)) {
			throw new InvalidArgumentException('Catalog number must be numeric.');
		}

		try {
    		$config = require '../app/config/db.php';

    		$database = new Database($config);
    		$catalog = new Catalog($database->getConnection());
		} catch (Throwable $exception) {
			http_response_code(400);

			exit(
				htmlspecialchars(
					$exception->getMessage(),
					ENT_QUOTES,
					'UTF-8'
				)
			);
		}

		$this->catalog_number = $catalogNumber;
		$this->quantity = $quantity;
		$this->name = $catalog->getName($catalogNumber);
		$this->price = $catalog->getPrice($catalogNumber);
	}

	public function getCatalogNumber(): string 
	{
		return $this->catalog_number;
	}

	public function getQuantity(): int 
	{
		return $this->quantity;
	}

	public function getName(): string 
	{
		return $this->name;
	}

	public function getPrice(): string 
	{
		return $this->price;
	}
}