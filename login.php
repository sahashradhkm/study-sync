<?php
declare(strict_types=1);

require_once 'includes/db.php';
require_once 'includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember_me']);

    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];

        if ($remember) {
            setcookie('studysync_user', $user['username'], time() + (86400 * 7), '/');
        }

        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid credentials. Please try again.';
}

$pageTitle = 'StudySync | Login';
require_once 'includes/header.php';
?>
<section class="container form-shell">
    <p class="tag">Welcome Back</p>
    <h1>Login</h1>
    <p class="page-intro">Continue your study streak and resume your focus cycle.</p>
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success">
            <p><?= htmlspecialchars($_SESSION['flash_success']); ?></p>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="alert alert-error">
            <p><?= htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>

    <form class="app-form" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label class="checkbox-row" for="remember_me">
            <input type="checkbox" id="remember_me" name="remember_me">
            Remember me for 7 days
        </label>

        <button class="btn" type="submit">Login</button>
    </form>
</section>
<?php require_once 'includes/footer.php'; ?>
