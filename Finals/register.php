<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';


$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/register.css">
    <title>Register - FindHire</title>
</head>
<body>
    <div class="container">
        <h1>Create an Account</h1>
        <h3>Please fill out the form below!</h3>
        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="core/handleforms.php" method="POST">
            <p><label for="username">Username:</label>
            <input type="text" name="username" id="username" required></p>

            <p><label for="user_password">Password:</label>
            <input type="password" name="user_password" id="user_password" required></p>

            <p><label for="role">Select Role:</label>
            <select name="role" id="role" required>
                <option value="APPLICANT">Applicant</option>
                <option value="HR">HR</option>
            </select></p>

            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>