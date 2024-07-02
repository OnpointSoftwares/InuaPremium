<?php
include 'includes/header.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    add_user($_POST['name'], $_POST['email'], $_POST['password'], 'client');
}
?>

<h2>Register</h2>
<form method="post" action="">
    <input type="text" name="name" required placeholder="Full Name">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Register</button>
</form>

<?php include 'includes/footer.php'; ?>
