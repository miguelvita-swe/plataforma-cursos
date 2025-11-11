<?php
require_once __DIR__ . '/../includes/courses-functions.php';
require __DIR__ . '/../includes/page-top.php';

$courseId = $_GET['id'] ?? '';
$course = null;
if ($courseId !== '') {
    $course = getCourseById((string)$courseId);
}
?>

<?php if ($course): ?>
    <h1><?= htmlspecialchars($course['title']) ?></h1>
    <h4>Instrutor: <?= htmlspecialchars($course['instructor'] ?? '') ?></h4>
    <p><?= htmlspecialchars($course['short_description'] ?? '') ?></p>

    <?php if (!empty($course['modules']) && is_array($course['modules'])): ?>
        <div class="mt-4">
            <h5>Módulos do Curso:</h5>
            <ul class="list-group">
                <?php foreach ($course['modules'] as $module): ?>
                    <li class="list-group-item"><?= htmlspecialchars($module) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="container">
        <h2 class="text-danger">Curso não encontrado!</h2>
        <p>O ID fornecido não corresponde a nenhum curso em nosso catálogo.</p>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/page-bottom.php';
