<?php
declare(strict_types=1);

require_once 'includes/db.php';
require_once 'includes/auth.php';
require_login();

$userId = (int) $_SESSION['user_id'];
$taskError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($title === '') {
            $taskError = 'Task title cannot be empty.';
        } else {
            $insert = $pdo->prepare('INSERT INTO tasks (user_id, title, description, status) VALUES (:user_id, :title, :description, :status)');
            $insert->execute([
                'user_id' => $userId,
                'title' => $title,
                'description' => $description,
                'status' => 'pending',
            ]);
            header('Location: dashboard.php');
            exit;
        }
    }

    if ($action === 'update') {
        $taskId = (int) ($_POST['task_id'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        $allowed = ['pending', 'in_progress', 'done'];

        if (in_array($status, $allowed, true)) {
            $update = $pdo->prepare('UPDATE tasks SET status = :status WHERE id = :id AND user_id = :user_id');
            $update->execute([
                'status' => $status,
                'id' => $taskId,
                'user_id' => $userId,
            ]);
        }

        header('Location: dashboard.php');
        exit;
    }

    if ($action === 'delete') {
        $taskId = (int) ($_POST['task_id'] ?? 0);
        $delete = $pdo->prepare('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
        $delete->execute([
            'id' => $taskId,
            'user_id' => $userId,
        ]);

        header('Location: dashboard.php');
        exit;
    }
}

$taskStmt = $pdo->prepare('SELECT id, title, description, status, created_at FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC');
$taskStmt->execute(['user_id' => $userId]);
$tasks = $taskStmt->fetchAll();

$pageTitle = 'StudySync | Dashboard';
require_once 'includes/header.php';
?>
<section class="container dashboard-grid">
    <aside class="content-card card-featured">
        <p class="tag">Control Room</p>
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></h2>
        <?php if (isset($_COOKIE['studysync_user'])): ?>
            <p class="mini-note">Cookie active for: <?= htmlspecialchars($_COOKIE['studysync_user']); ?></p>
        <?php endif; ?>
        <div class="timer-box timer-hero" id="timerCard">
            <p class="timer-label">Pomodoro Timer</p>
            <p id="timerDisplay" aria-live="polite">25:00</p>
            <p class="mini-note">25-minute deep work sprint</p>
            <div class="timer-actions">
                <button id="startTimer" class="btn" type="button">Start</button>
                <button id="resetTimer" class="btn btn-outline" type="button">Reset</button>
            </div>
        </div>
    </aside>

    <section class="content-card card-subtle">
        <p class="tag">Task Builder</p>
        <h2>Add Study Task</h2>
        <?php if ($taskError !== ''): ?>
            <div class="alert alert-error"><p><?= htmlspecialchars($taskError); ?></p></div>
        <?php endif; ?>
        <form class="app-form" method="POST">
            <input type="hidden" name="action" value="create">
            <label for="title">Task Title</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"></textarea>

            <button class="btn" type="submit">Add Task</button>
        </form>
    </section>
</section>

<section class="container content-card card-subtle">
    <p class="tag">Execution Board</p>
    <h2>Your Tasks</h2>
    <?php if (!$tasks): ?>
        <p>No tasks found. Add your first task above.</p>
    <?php else: ?>
        <div class="task-table-wrap">
            <table class="task-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <?php
                        $statusMap = [
                            'pending' => ['label' => 'Pending', 'class' => 'status-pending'],
                            'in_progress' => ['label' => 'In Progress', 'class' => 'status-progress'],
                            'done' => ['label' => 'Done', 'class' => 'status-done'],
                        ];
                        $statusMeta = $statusMap[$task['status']] ?? $statusMap['pending'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($task['title']); ?></td>
                            <td><?= htmlspecialchars($task['description']); ?></td>
                            <td>
                                <span class="task-status-badge <?= htmlspecialchars($statusMeta['class']); ?>"><?= htmlspecialchars($statusMeta['label']); ?></span>
                                <form method="POST" class="inline-form">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="task_id" value="<?= (int) $task['id']; ?>">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="done" <?= $task['status'] === 'done' ? 'selected' : ''; ?>>Done</option>
                                    </select>
                                </form>
                            </td>
                            <td><?= htmlspecialchars($task['created_at']); ?></td>
                            <td>
                                <form method="POST" class="inline-form" onsubmit="return confirm('Delete this task?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="task_id" value="<?= (int) $task['id']; ?>">
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require_once 'includes/footer.php'; ?>
