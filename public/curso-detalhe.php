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
    <div class="detail-page">
        <div class="detail-header">
            <div class="detail-content">
                <h1><?= htmlspecialchars($course['title']) ?></h1>
                <h4>Ministrado por: <?= htmlspecialchars($course['instructor'] ?? '') ?></h4>
                <p><?= htmlspecialchars($course['short_description'] ?? '') ?></p>
            </div>
            <?php if (!empty($course['img'])): ?>
                <div class="detail-image-container">
                    <img src="<?= htmlspecialchars($course['img']) ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="detail-image" />
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($course['modules']) && is_array($course['modules'])): ?>
            <div class="mt-4">
                <h5>Módulos do Curso:</h5>
                <ul class="list-group">
                    <?php foreach ($course['modules'] as $module): ?>
                        <li class="list-group-item">
                            <span class="module-icon">✓</span> <?= htmlspecialchars($module) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="detail-actions mt-4">
            <a href="/cursos.php" class="btn btn-back">← Voltar ao Catálogo</a>
            <a href="#" class="btn btn-enroll">Inscrever-se Agora</a>
        </div>
    </div>

<?php else: ?>
    <div class="detail-page">
        <h2 class="text-danger">❌ Curso não encontrado!</h2>
        <p>O ID fornecido não corresponde a nenhum curso em nosso catálogo.</p>
        <div class="detail-actions mt-4">
            <a href="/cursos.php" class="btn">Voltar ao Catálogo</a>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/page-bottom.php';
