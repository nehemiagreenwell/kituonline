<?php

class Order 
{
	private mysqli $connection;
	private int $orderNumber = 0;
	private array $customerData = [];

	public function __construct(mysqli $connection) 
	{
		$this->connection = $connection;
	}

	public function createOrder(): int 
	{
		$sql = "INSERT INTO customer_order (submitted) VALUES ('no')";
		$stmt = $this->connection->prepare($sql);

		$result = $stmt->execute();

		if ($result === false) {
			throw new RuntimeException('Could not create order:');
		}

		$this->orderNumber = (int) $this->connection->insert_id;
		$_SESSION['order_number'] = $this->orderNumber;
	}

	public function selectOrder(int $orderNumber): void 
	{
		if ($orderNumber < 1) {
            throw new InvalidArgumentException('Invalid order number.');
        }

		$this->orderNumber = $orderNumber;

		$stmt = $this->connection->prepare('SELECT * FROM customer_order WHERE order_number = ?');
		$stmt->bind_param('i', $orderNumber);
		$stmt->execute();

		$row = $stmt->get_result()->fetch_assoc();

		if ($row === null) {
			throw new RuntimeException('Order not found.');
		}

		$this->customerData = $row;
	}

	public function setCustomerInfo(array $data): void 
	{

		if ($this->orderNumber < 1) {
            throw new RuntimeException('No order has been selected.');
        }

		$allowed = ['first_name', 'last_name', 'street', 'city', 'state','zip','email','phone'];

		$updates = [];
		$params = [];
		$types = '';

		foreach ($data as $key => $value) {
			if (in_array($key, $allowed, true)) {
				$updates[] = "{$key} = ?";
				$params[] = trim((string) $value);
				$types .= 's';
			}
		}

		if (!$updates) {
			return;
		}

		$sql = 'UPDATE customer_order SET %s WHERE order_number = ? ' . implode(', ',$updates);
		$stmt = $this->connection->prepare($sql);

		$bindValues = array_merge($params, [(string) $this->orderNumber]);
		$types .= 's';

		$stmt->bind_param($types, ...$bindValues);

		if (!$stmt->execute()) {
			throw new RuntimeException('Could not update shipping information.');
		}
	}

	public function markSubmitted(): void 
	{
		if ($this->orderNumber < 1) {
            throw new RuntimeException('No order has been selected.');
        }

        $stmt = $this->connection->prepare(
            "UPDATE customer_order
             SET submitted = 'yes'
             WHERE order_number = ?"
        );

        $stmt->bind_param('i', $this->orderNumber);
        $stmt->execute();

	}

	public function cancel(): void
    {
        if ($this->orderNumber < 1) {
            return;
        }

        $stmt = $this->connection->prepare(
            'DELETE FROM customer_order WHERE order_number = ?'
        );

        $stmt->bind_param('i', $this->orderNumber);
        $stmt->execute();
    }

    public function getOrderNumber(): int
    {
        return $this->orderNumber;
    }

    public function getCustomerData(): array
    {
        return $this->customerData;
    }


}