<?php
require __DIR__ . '/config.php';
$q = trim((string)($_GET['q'] ?? ''));
if (strlen($q) > 100) $q = substr($q, 0, 100);
if ($q !== '') {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE full_name LIKE ? OR student_number LIKE ? OR email LIKE ? OR course LIKE ? ORDER BY id DESC');
    $like = '%' . $q . '%';
    $stmt->execute([$like, $like, $like, $like]);
} else {
    $stmt = $pdo->query('SELECT * FROM students ORDER BY id DESC');
}
$students = $stmt->fechAll();
$total = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!doctype html>

<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Students · Admin</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<aside class="sidebar"><div class="brand"><span class="brandmark">S</span> STUDIO<span class="muted"> / admin</span></div><div class="nav-label">WORKSPACE</div><a class="nav active" href="index.php">▦ &nbsp; Students</a><div class="sidebar-bottom">Student Management<br><small>PHP + MySQL</small></div></aside>
<main class="main"><header class="topbar"><span>Worksspk lkolkace <span class="muted">/ Studenjts</span></span><span class="status"><i></i> Database connected</span></header><div class="content">
<div class="heading"><div><div class="eyebrow">MANAGEMENT / 01</div><h1>Students</h1><p class="subtitle">Manage student records in one place.</p></div><button class="primary" onclick="openModal()">+ &nbsp; Add student</button></div>
<?php if ($flash): ?><div class="alert <?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= e($flash['message']) ?></div><?php endif; ?>
<div class="stat"><div class="stat-label">TOTAL STUDENTS</div><div class="stat-number"><?= $total ?></div><div class="stat-note">Registered in the database</div></div>
<section class="panel"><div class="panel-head"><div><h2>Student directory</h2><p>View, edit, and manage all records.</p></div><form method="get" class="search"><input type="search" name="q" placeholder="Search students..." value="<?= e($q) ?>"><button type="submit">Search</button></form></div>
<div class="table-scroll"><table><thead><tr><th>STUDENT NO.</th><th>FULL NAME</th><th>EMAIL</th><th>COURSE</th><th>YEAR</th><th>ACTIONS</th></tr></thead><tbody>
<?php foreach ($students as $s): ?><tr><td class="mono"><?= e($s['student_number']) ?></td><td><strong><?= e($s['full_name']) ?></strong></td><td><?= e($s['email']) ?></td><td><?= e($s['course']) ?></td><td><span class="year">Year <?= (int)$s['year_level'] ?></span></td><td><div class="actions"><button class="text-button" type="button" onclick="editStudent(<?= e(json_encode($s, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) ?>)">Edit</button><form method="post" action="action.php" onsubmit="return confirm('Delete this student permanently?')"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$s['id'] ?>"><button class="text-button danger" type="submit">Delete</button></form></div></td></tr><?php endforeach; ?>
<?php if (!$students): ?><tr><td colspan="6" class="empty">No students found. Add a student to get started.</td></tr><?php endif; ?>
</tbody></table></div><div class="panel-footer">Showing <?= count($students) ?> record(s)<?= $q !== '' ? ' matching your search' : '' ?></div></section>
</div></main>
<div class="overlay" id="overlay" hidden><div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle"><div class="modal-head"><div><div class="eyebrow">STUDENT RECORD</div><h2 id="modalTitle">Add student</h2></div><button class="close" type="button" onclick="closeModal()" aria-label="Close">×</button></div><form method="post" action="action.php" id="studentForm"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><input type="hidden" name="action" id="formAction" value="create"><input type="hidden" name="id" id="studentId"><label>Student number<input name="student_number" id="studentNumber" maxlength="30" required placeholder="2026-0001"></label><label>Full name<input name="full_name" id="fullName" maxlength="120" required placeholder="Juan Dela Cruz"></label><label>Email address<input type="email" name="email" id="email" maxlength="190" required placeholder="juan@example.com"></label><div class="two"><label>Course<input name="course" id="course" maxlength="100" required placeholder="BS Information Systems"></label><label>Year level<select name="year_level" id="yearLevel" required><option value="1">1st Year</option><option value="2">2nd Year</option><option value="3">3rd Year</option><option value="4">4th Year</option><option value="5">5th Year</option><option value="6">6th Year</option></select></label></div><div class="modal-actions"><button type="button" class="secondary" onclick="closeModal()">Cancel</button><button type="submit" class="primary" id="saveButton">Save student</button></div></form></div></div>
<script src="assets/app.js"></script></body></html>