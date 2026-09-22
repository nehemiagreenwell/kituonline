<?php
require 'config/Database.php';
require 'classes/User.php';

$db = (new Database())->connect();
$user = new User($db);
$user->logout();
