<?php
require 'config/Database.php';
require 'classes/User.php';

$db = (new Database())->connect();
$user = new User($db);

if (!$user->isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
<h1>Welcome <?php echo $_SESSION['user_name']; ?>!</h1>
<p>You are logged in.</p>
<a href="logout.php">Logout</a>
</body>
</html>
