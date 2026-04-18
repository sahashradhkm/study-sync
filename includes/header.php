<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');

function nav_link_class(string $page, string $currentPage): string
{
    return $page === $currentPage ? 'nav-link active' : 'nav-link';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'StudySync'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="index.php">StudySync</a>
        <nav class="main-nav">
            <a class="<?= nav_link_class('index.php', $currentPage); ?>" href="index.php">Home</a>
            <a class="<?= nav_link_class('about.php', $currentPage); ?>" href="about.php">About</a>
            <a class="<?= nav_link_class('contact.php', $currentPage); ?>" href="contact.php">Contact</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="<?= nav_link_class('dashboard.php', $currentPage); ?>" href="dashboard.php">Dashboard</a>
                <a class="<?= nav_link_class('profile.php', $currentPage); ?>" href="profile.php">Profile</a>
                <a class="nav-link" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="<?= nav_link_class('register.php', $currentPage); ?>" href="register.php">Register</a>
                <a class="<?= nav_link_class('login.php', $currentPage); ?>" href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="page-main">
