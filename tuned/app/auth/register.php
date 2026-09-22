<?php
require 'config/Database.php';
require 'classes/User.php';

$db = (new Database())->connect();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = $user->register($_POST['name'], $_POST['email'], $_POST['password']);
    if ($result === true) {
        echo "Registered! <a href='login.php'>Login now</a>";
    } else {
        echo $result;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Full name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Register</button>
</form>
<p>Already have account? <a href="login.php">Login</a></p>
</body>
</html>
