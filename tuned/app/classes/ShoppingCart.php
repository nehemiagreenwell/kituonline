<?php

class ShoppingCart
{
    private array $items = [];

    public function __construct()
    {
        $items = $_SESSION['cart'] ?? [];

        if (is_array($items)) {
            $this->items = $items;
        }
    }

    public function addItem(Item $item): void
    {
        $catalogNumber = $item->getCatalogNumber();

        foreach ($this->items as &$existingItem) {
            if ($existingItem['catalog_number'] === $catalogNumber) {
                $existingItem['quantity'] += $item->getQuantity();
                $this->save();
                return;
            }
        }

        $this->items[] = [
            'catalog_number' => $item->getCatalogNumber(),
            'quantity' => $item->getQuantity(),
            'name' => $item->getName(),
            'price' => $item->getPrice(),
        ];

        $this->save();
    }

    public function updateQuantity(string $catalogNumber, int $quantity): void
    {
        foreach ($this->items as $index => &$item) {
            if ($item['catalog_number'] === $catalogNumber) {
                if ($quantity <= 0) {
                    unset($this->items[$index]);
                } else {
                    $item['quantity'] = $quantity;
                }

                $this->items = array_values($this->items);
                $this->save();
                return;
            }
        }
    }

    public function removeItem(string $catalogNumber): void
    {
        $this->items = array_values(
            array_filter(
                $this->items,
                fn (array $item): bool =>
                    $item['catalog_number'] !== $catalogNumber
            )
        );

        $this->save();
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function getItemCount(): int
    {
        return count($this->items);
    }

    public function getTotal(): float
    {
        $total = 0.0;

        foreach ($this->items as $item) {
            $total += (float) $item['price'] * (int) $item['quantity'];
        }

        return $total;
    }

    public function clear(): void
    {
        $this->items = [];
        $this->save();
    }

    private function save(): void
    {
        $_SESSION['cart'] = $this->items;
    }
}
