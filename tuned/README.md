# Kituonline

A PHP and MySQL online catalog and ordering application.

## Requirements

- PHP 8.1 or newer
- MySQL or MariaDB
- Composer
- PHP MySQLi extension
- PHP mbstring extension
- PHP XML extension

## Project structure

app/
├── bootstrap.php
├── classes/
│   ├── Catalog.php
│   ├── Database.php
│   ├── Item.php
│   ├── Order.php
│   ├── ShoppingCart.php
│   └── Validation.php
└── config/
    └── db.php

public/
├── index.php
├── catalog.php
├── shoppingcart.php
└── processorder.php

tests/
└── ValidationTest.php

composer.json
phpunit.xml
.gitignore
README.md
