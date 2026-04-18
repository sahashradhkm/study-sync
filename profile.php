<?php
declare(strict_types=1);

require_once 'includes/db.php';
require_once 'includes/auth.php';
require_login();

$userId = (int) $_SESSION['user_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'File upload failed.';
    } else {
        $maxSize = 2 * 1024 * 1024;
        $allowedMime = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $mime = mime_content_type($file['tmp_name']);

        if (!isset($allowedMime[$mime])) {
            $errors[] = 'Only JPG and PNG files are allowed.';
        }

        if ($file['size'] > $maxSize) {
            $errors[] = 'File must be smaller than 2MB.';
        }

        if (!$errors) {
            $extension = $allowedMime[$mime];
            $newName = 'user_' . $userId . '_' . time() . '.' . $extension;
            $targetPath = __DIR__ . '/uploads/' . $newName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $update = $pdo->prepare('UPDATE users SET profile_pic = :profile_pic WHERE id = :id');
                $update->execute([
                    'profile_pic' => $newName,
                    'id' => $userId,
                ]);
                $success = 'Profile picture updated.';
            } else {
                $errors[] = 'Could not move uploaded file.';
            }
        }
    }
}

$stmt = $pdo->prepare('SELECT username, email, profile_pic FROM users WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch();

$pageTitle = 'StudySync | Profile';
require_once 'includes/header.php';
?>
<section class="container content-card">
    <p class="tag">Identity</p>
    <h1>My Profile</h1>
    <p class="page-intro">Your account details and profile photo for personalized StudySync sessions.</p>
    <p><strong>Username:</strong> <?= htmlspecialchars((string) $user['username']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars((string) $user['email']); ?></p>

    <?php if (!empty($user['profile_pic'])): ?>
        <img class="profile-pic" src="uploads/<?= htmlspecialchars((string) $user['profile_pic']); ?>" alt="Profile picture">
    <?php endif; ?>

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

    <form class="app-form" method="POST" enctype="multipart/form-data">
        <label for="profile_pic">Upload profile picture (JPG/PNG)</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/jpeg,image/png" required>
        <p class="mini-note">Max upload size: 2MB. Best results with square images.</p>
        <button class="btn" type="submit">Upload</button>
    </form>
</section>
<?php require_once 'includes/footer.php'; ?>
