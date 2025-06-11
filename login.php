<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $users = json_decode(file_get_contents('users.json'), true);
    $input_user = $_POST['username'];
    $input_pass = $_POST['password'];
    $error = "Access Denied";

    foreach ($users as $user) {
        if ($user['username'] === $input_user && password_verify($input_pass, $user['password'])) {
             $_SESSION['logged_in'] = true;
             header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 400px;
            margin: 80px auto;
            padding: 20px;
            border: 2px dashed #303D3F;
            border-radius: 8px;
            background: #242B2D;
            color: #D0D2D2;
        }
        h1 {
            text-align: center;
            margin-bottom: 25px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 6px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px;
            background: #303D3F;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Login</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="username">Username</label>
        <input name="username" id="username" type="text" required autofocus>

        <label for="password">Password</label>
        <input name="password" id="password" type="password" required>

        <input type="submit" value="Log In">
    </form>
</body>
</html>