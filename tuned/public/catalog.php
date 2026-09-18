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
        </article>

        <hr>
    <?php endforeach; ?>
</body>
</html>
