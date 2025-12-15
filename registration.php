<?php
$name = "";
$email = "";
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($name)) {
        $errors['name'] = "Name is required.";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    if (empty($errors)) {

        $file = "users.json";
        $data = file_get_contents($file);
        $users = json_decode($data, true);

        if (!is_array($users)) {
            $users = [];
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $users[] = [
            "name" => $name,
            "email" => $email,
            "password" => $hashed
        ];

        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

        $success = "Registration Successful!";
        $name = $email = "";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>User Registration</h2>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
        <div class="error"><?= $errors['name'] ?? "" ?></div>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
        <div class="error"><?= $errors['email'] ?? "" ?></div>

        <label>Password</label>
        <input type="password" name="password">
        <div class="error"><?= $errors['password'] ?? "" ?></div>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password">
        <div class="error"><?= $errors['confirm_password'] ?? "" ?></div>

        <button type="submit">Register</button>

    </form>
</div>

</body>
</html>
