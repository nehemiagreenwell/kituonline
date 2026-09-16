<?php

class Catalog
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getCategoriesAndTypes(): array
    {
        $sql = 'SELECT DISTINCT category, type FROM food ORDER BY category, type';
        $result = $this->connection->query($sql);

        if ($result === false) {
            throw new RuntimeException('Could not load categories: ' . $this->connection->error);
        }

        $categories = [];

        while ($row = $result->fetch_assoc()) {
            $categories[$row['category']][] = $row['type'];
        }

        return $categories;
    }

    public function getAllOfType(string $type): array
    {
        if ($type === '') {
            throw new InvalidArgumentException('Type cannot be empty.');
        }

        $stmt = $this->connection->prepare('SELECT * FROM food WHERE type = ? ORDER BY name');
        $stmt->bind_param('s', $type);
        $stmt->execute();

        $result = $stmt->get_result();
        $items = [];

        while ($row = $result->fetch_object()) {
            $items[] = $row;
        }

        return $items;
    }

    public function getName(string $catalogNumber): string
    {
        if (!preg_match('/^\d+$/', $catalogNumber)) {
            throw new InvalidArgumentException('Catalog number must be numeric.');
        }

        $stmt = $this->connection->prepare('SELECT name, type FROM food WHERE catalog_number = ?');
        $stmt->bind_param('s', $catalogNumber);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if ($row === null) {
            throw new RuntimeException('Product not found.');
        }

        return $row['name'] . ' ' . $row['type'];
    }

    public function getPrice(string $catalogNumber): string
    {
        if (!preg_match('/^\d+$/', $catalogNumber)) {
            throw new InvalidArgumentException('Catalog number must be numeric.');
        }

        $stmt = $this->connection->prepare('SELECT price FROM food WHERE catalog_number = ?');
        $stmt->bind_param('s', $catalogNumber);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if ($row === null) {
            throw new RuntimeException('Product not found.');
        }

        return (string) $row['price'];
    }
}
