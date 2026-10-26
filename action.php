<?php
declare(strict_types=1);
require __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed'); }
if (!hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? ''))) { http_response_code(403); exit('Invalid CSRF token'); }
$action = (string)($_POST['action'] ?? '');
if ($action === 'delete') {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if (!$id || $id < 1) redirect('Invalid student ID.', 'error');
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
    $stmt->execute([$id]);
    redirect($stmt->rowCount() ? 'Student deleted.' : 'Student not found.', $stmt->rowCount() ? 'success' : 'error');
}
if (!in_array($action, ['create', 'update'], true)) { http_response_code(400); exit('Invalid action'); }
$number = trim((string)($_POST['student_number'] ?? ''));
$name = trim((string)($_POST['full_name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$course = trim((string)($_POST['course'] ?? ''));
$year = filter_var($_POST['year_level'] ?? null, FILTER_VALIDATE_INT);
if ($number === '' || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $course === '' || $year === false || $year < 1 || $year > 6 || strlen($number) > 30 || strlen($name) > 120 || strlen($email) > 190 || strlen($course) > 100) {
    redirect('Please provide valid student details.', 'error');
}
try {
    if ($action === 'create') {
        $stmt = $pdo->prepare('INSERT INTO students (student_number, full_name, email, course, year_level) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$number, $name, $email, $course, $year]);
        redirect('Student added successfully.');
    }
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if (!$id || $id < 1) redirect('Invalid student ID.', 'error');
    $stmt = $pdo->prepare('UPDATE students SET student_number=?, full_name=?, email=?, course=?, year_level=? WHERE id=?');
    $stmt->execute([$number, $name, $email, $course, $year, $id]);
    redirect('Student updated successfully.');
} catch (PDOException $e) {
    if ($e->getCode() === '23000') redirect('Student number or email already exists.', 'error');
    error_log($e->getMessage());
    redirect('Database operation failed.', 'error');
}
