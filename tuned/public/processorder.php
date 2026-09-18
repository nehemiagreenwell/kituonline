<?php

require_once __DIR__ . '/../app/bootstrap.php';

try {
    $config = require __DIR__ . '/../app/config/db.php';

    $database = new Database($config);
    $order = new Order($database->getConnection());
    $cart = new ShoppingCart();

    if ($cart->isEmpty()) {
        header('Location: shoppingcart.php');
        exit;
    }

    if (!isset($_SESSION['order_number'])) {
        $order->createOrder();
    } else {
        $order->selectOrder(
            (int) $_SESSION['order_number']
        );
    }

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = validate_shipping_data($_POST);

        if (empty($errors)) {
            $order->setCustomerInfo($_POST);
            $order->markSubmitted();

            $cart->clear();

            unset($_SESSION['order_number']);

            header('Location: processorder.php?success=1');
            exit;
        }
    }
} catch (Throwable $exception) {
    http_response_code(500);
    exit('Unable to process order.');
}

if (isset($_GET['success'])):
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Complete</title>
</head>
<body>
    <h1>Order submitted successfully.</h1>

    <p>
        <a href="index.php">Return to catalog</a>
    </p>
</body>
</html>

<?php
exit;
endif;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h1>Shipping Information</h1>

    <?php if (!empty($errors)): ?>
        <div>
            <h2>Please correct the following errors:</h2>

            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>
                        <?= htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="processorder.php">
        <p>
            <label>
                First name:
                <input
                    type="text"
                    name="first_name"
                    value="<?= htmlspecialchars(
                        $_POST['first_name'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </label>
        </p>

        <            <label>
                Last name:
                <input
                    type="text"
                    name="last_name"
                    value="<?= htmlspecialchars(
                        $_POST['last_name'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </label>
        </p>

        <p>
            <label>
                Street:
                <input
                    type="text"
                    name="street"
                    value="<?= htmlspecialchars(
                        $_POST['street'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </label>
        </p>

        <p>
            <label>
                City:
                <input
                    type="text"
                    name="city"
                    value="<?= htmlspecialchars(
                        $_POST['city'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </label>
        </p>

        <p>
            <label>
                State:
                <input
                    type="text"
                    name="state"
                    value="<?= htmlspecialchars(
                        $_POST['state'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </label>
        </p>

        <p>
            <label>
                ZIP code:
                <input
                    type="text"
                    name="zip"
                    value="<?= htmlspecialchars(
                        $_POST['zip'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </label>
        </p>

        <p>
            <label>
                Email:
                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars(
                        $_POST['email'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </label>
        </p>

        <p>
            <label>
                Phone:
                <input
                    type="text"
                    name="phone"
                    value="<?= htmlspecialchars(
                        $_POST['phone'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </label>
        </p>

        <button type="submit">
            Submit Order
        </button>
    </form>
</body>
</html>
