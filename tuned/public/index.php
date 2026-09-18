<?php

require_once __DIR__ . '/../app/bootstrap.php';

try {
    $config = require __DIR__ . '/../app/config/db.php';

    $database = new Database($config);
    $catalog = new Catalog($database->getConnection());
    $categories = $catalog->getCategoriesAndTypes();
} catch (Throwable $exception) {
    http_response_code(500);
    exit('Unable to load catalog.');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kituonline Catalog</title>
</head>
<body>
    <h1>Kituonline Catalog</h1>

    <?php foreach ($categories as $category => $types): ?>
        <h2>
            <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>
        </h2>

        <?php foreach ($types as $type): ?>
            <p>
                <a href="catalog.php?type=<?= urlencode($type) ?>">
                    <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>
                </a>
            </p>
        <?php endforeach; ?>
    <?php endforeach; ?>
</body>
</html>


