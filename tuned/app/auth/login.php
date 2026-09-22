<?php
require 'config/Database.php';
require 'classes/User.php';

$db = (new Database())->connect();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user->login($_POST['email'], $_POST['password'])) {

        if(isset($_GET['redirect'])) {
            header("Location: /onlineshopping_oo/tuned/public/".$_GET['redirect']);
        } else {
            header("Location: /onlineshopping_oo/tuned/public/index.php");
        }

        exit;
    } else {
        echo "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>
<p>Don't have account? <a href="register.php">Register</a></p>
</body>
</html>
