<?php

require_once __DIR__ . '/../app/bootstrap.php';

$type = trim((string) ($_GET['type'] ?? ''));

if ($type === '') {
    header('Location: index.php');
    exit;
}

try {
    $config = require __DIR__ . '/../app/config/db.php';

    $database = new Database($config);
    $catalog = new Catalog($database->getConnection());
    $items = $catalog->getAllOfType($type);
    $isLoggedIn = isset($_SESSION['user_id']); // comes from logsys
} catch (Throwable $exception) {
    http_response_code(500);
    exit('Unable to load products.');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>
    </title>
</head>
<body>
    <p>
        <a href="index.php">Back to catalog</a>
    </p>
    <p style="display:inline;">Welcome, <?= $_SESSION['user_name'] ?></p>
    <a href="../../../php-sandbox/logsys/logout.php">
            <p><small>Logout</small></p>
            </a>

    <h1>
        <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>
    </h1>

    <?php if (empty($items)): ?>
        <p>No products were found.</p>
    <?php endif; ?>

    <?php foreach ($items as $item): ?>
        <article>
            <h2>
                <?= htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?>
            </h2>

            <p>
                Price:
                $<?= htmlspecialchars((string) $item->price, ENT_QUOTES, 'UTF-8') ?>
            </p>
            <?php if($isLoggedIn): ?>
            <!-- LOGGED IN USER -->
            <form method="post" action="shoppingcart.php">
                <input
                    type="hidden"
                    name="catalog_number"
                    value="<?= htmlspecialchars(
                        (string) $item->catalog_number,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <label>
                    Quantity:
                    <input
                        type="number"
                        name="quantity"
                        value="1"
                        min="1"
                        required
                    >
                </label>

                <button type="submit" name="add_to_cart" value="1">
                    Add to Cart
                </button>
            </form>
            
        <?php else: ?>
            <!-- PUBLIC USER -->
            <a href="../../../php-sandbox/logsys/login.php?redirect=catalog.php?id=$item->catalog_number">
            <p><small>Login required to add to cart</small></p>
            </a>
        <?php endif; ?>
        </article>

        <hr>
    <?php endforeach; ?>
</body>
</html>
