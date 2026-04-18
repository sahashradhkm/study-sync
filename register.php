<?php
declare(strict_types=1);

require_once 'includes/db.php';
require_once 'includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters long.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if (!$errors) {
        $checkStmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $checkStmt->execute(['email' => $email]);

        if ($checkStmt->fetch()) {
            $errors[] = 'This email is already registered.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)');
            $insertStmt->execute([
                'username' => $username,
                'email' => $email,
                'password_hash' => $passwordHash,
            ]);

            $_SESSION['flash_success'] = 'Registration successful. Please log in.';
            header('Location: login.php');
            exit;
        }
    }
}

$pageTitle = 'StudySync | Register';
require_once 'includes/header.php';
?>
<section class="container form-shell">
    <p class="tag">Start Here</p>
    <h1>Create Account</h1>
    <p class="page-intro">Set up your StudySync account and design your daily workflow.</p>
    <?php if ($errors): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="registerForm" class="app-form" method="POST" novalidate>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username); ?>" required minlength="3">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required minlength="6">

        <button class="btn" type="submit">Register</button>
    </form>
</section>
<?php require_once 'includes/footer.php'; ?>
