<?php

require_once __DIR__ . '/../app/bootstrap.php';

try {
    $config = require __DIR__ . '/../app/config/db.php';

    $database = new Database($config);
    $catalog = new Catalog($database->getConnection());
    $cart = new ShoppingCart();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_to_cart'])) {
            $catalogNumber = trim(
                (string) ($_POST['catalog_number'] ?? '')
            );

            $quantity = filter_var(
                $_POST['quantity'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (
                !preg_match('/^\d+$/', $catalogNumber) ||
                $quantity === false ||
                $quantity < 1
            ) {
                throw new InvalidArgumentException(
                    'Invalid product or quantity.'
                );
            }

            $catalogName = trim(
                (string) ($_POST['catalog_name'] ?? '')
            );

            $price = trim(
                (int) ($_POST['price'] ?? '')
            );

            $item = new Item(
                $catalogNumber,
                $quantity,
                $catalogName,
                $price
            );

            $cart->addItem($item);

            header('Location: shoppingcart.php');
            exit;
        }

        if (isset($_POST['update_cart'])) {
            $catalogNumber = trim(
                (string) ($_POST['catalog_number'] ?? '')
            );

            $quantity = filter_var(
                $_POST['quantity'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (
                !preg_match('/^\d+$/', $catalogNumber) ||
                $quantity === false
            ) {
                throw new InvalidArgumentException(
                    'Invalid cart data.'
                );
            }

            $cart->updateQuantity(
                $catalogNumber,
                $quantity
            );

            header('Location: shoppingcart.php');
            exit;
        }

        if (isset($_POST['remove_item'])) {
            $catalogNumber = trim(
                (string) ($_POST['catalog_number'] ?? '')
            );

            if (!preg_match('/^\d+$/', $catalogNumber)) {
                throw new InvalidArgumentException(
                    'Invalid product number.'
                );
            }

            $cart->removeItem($catalogNumber);

            header('Location: shoppingcart.php');
            exit;
        }
    }
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Shopping Cart</h1>

    <p>
        <a href="index.php">Continue shopping</a>
    </p>

    <?php if ($cart->isEmpty()): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($cart->getItems() as $item): ?>
            <article>
                <h2>
                    <?= htmlspecialchars(
                        $item['name'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </h2>

                <p>
                    Price:
                    $<?= htmlspecialchars(
                        (string) $item['price'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>

                <form method="post" action="shoppingcart.php">
                    <input
                        type="hidden"
                        name="catalog_number"
                        value="<?= htmlspecialchars(
                            $item['catalog_number'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >
                    <input
                        type="hidden"
                        name="catalog_name"
                        value="<?= htmlspecialchars(
                            $item['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >
                    <input
                        type="hidden"
                        name="price"
                        value="<?= htmlspecialchars(
                            $item['price'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                    <label>
                        Quantity:
                        <input
                            type="number"
                            name="quantity"
                            value="<?= (int) $item['quantity'] ?>"
                            min="0"
                            required
                        >
                    </label>

                    <button
                        type="submit"
                        name="update_cart"
                        value="1"
                    >
                        Update
                    </button>

                    <button
                        type="submit"
                        name="remove_item"
                        value="1"
                    >
                        Remove
                    </button>
                </form>
            </article>

            <hr>
        <?php endforeach; ?>

        <h2>
            Total:
            $<?= number_format($cart->getTotal(), 2) ?>
        </h2>

        <p>
            <a href="processorder.php">
                Proceed to checkout
            </a>
        </p>
    <?php endif; ?>
</body>
</html>


