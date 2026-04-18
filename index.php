<?php
$pageTitle = 'StudySync | Home';
require_once 'includes/header.php';
?>
<section class="hero container">
    <div class="hero-copy">
        <p class="tag">By Sahashradh | Capstone Project 23CSE404</p>
        <h1>Discipline Is A Design Problem. StudySync Solves It.</h1>
        <p class="page-intro">An editorial-style productivity workspace for students who want focus, structure, and measurable daily momentum.</p>
        <div class="hero-actions">
            <a class="btn" href="register.php">Get Started</a>
            <a class="btn btn-outline" href="dashboard.php">View Dashboard</a>
        </div>
    </div>
    <div class="hero-card card-featured">
        <h3>Focused By Design</h3>
        <ul>
            <li>Task planning with clean priority flow</li>
            <li>Pomodoro engine for distraction-free sessions</li>
            <li>Secure login stack powered by PHP + MySQL</li>
            <li>Responsive UI crafted for mobile and desktop</li>
        </ul>
    </div>
</section>

<section class="container story-grid">
    <article class="content-card card-subtle">
        <p class="tag">Daily System</p>
        <h2>Built For Consistency, Not Motivation</h2>
        <p>Break big goals into clear tasks, track each state, and remove decision fatigue from your routine.</p>
    </article>
    <article class="content-card card-stat">
        <p class="stat-label">Core Philosophy</p>
        <p class="stat-value">Study in cycles. Finish with intent.</p>
        <p class="mini-note">Pomodoro + task status updates create visible proof of work each day.</p>
    </article>
    <article class="content-card card-subtle">
        <p class="tag">Capstone Delivery</p>
        <h2>Frontend + Backend In One Product</h2>
        <p>This project combines HTML, CSS, JavaScript, PHP sessions/cookies, and MySQL CRUD in one complete workflow.</p>
    </article>
    <div class="content-card quote-block">
        <p>"Great study sessions are engineered, not accidental."</p>
        <span>Sahashradh</span>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>
