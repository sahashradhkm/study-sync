<?php
declare(strict_types=1);

require_once 'includes/db.php';

$errors = [];
$success = '';
$name = '';
$email = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }

    if (strlen($message) < 10) {
        $errors[] = 'Message should be at least 10 characters.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)');
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ]);

        $success = 'Thank you. Your message has been submitted successfully.';
        $name = $email = $message = '';
    }
}

$pageTitle = 'StudySync | Contact';
require_once 'includes/header.php';
?>
<section class="container form-shell">
    <p class="tag">Connect</p>
    <h1>Contact</h1>
    <p class="page-intro">Use this page to submit feedback or suggestions. This demonstrates PHP form handling with database insertion.</p>

    <?php if ($success !== ''): ?>
        <div class="alert alert-success"><p><?= htmlspecialchars($success); ?></p></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="contactForm" class="app-form" method="POST" novalidate>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name); ?>" required>

        <label for="email">Email</label>
        <input type="email" id="contactEmail" name="email" value="<?= htmlspecialchars($email); ?>" required>

        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($message); ?></textarea>

        <button class="btn" type="submit">Send Message</button>
    </form>
</section>
<?php require_once 'includes/footer.php'; ?>
