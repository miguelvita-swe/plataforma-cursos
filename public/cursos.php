<?php
require __DIR__ . '/../includes/page-top.php';
require_once __DIR__ . '/../includes/courses-functions.php';

$area = $_GET['area'] ?? '';
if ($area !== '') {
    $courses = getCoursesByArea((string)$area);
} else {
    $courses = getAllCourses();
}
?>

<h1>Catálogo de Cursos</h1>

<?php if (empty($courses)): ?>
    <p>Nenhum curso encontrado para a área selecionada.</p>
<?php else: ?>
    <div class="courses-grid">
        <?php foreach ($courses as $course): ?>
            <div class="card course-card">
                <?php if (!empty($course['img'])): ?>
                    <img src="<?= htmlspecialchars($course['img']) ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="card-img" />
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title">
                        <a href="/curso-detalhe.php?id=<?= urlencode($course['id']) ?>"><?= htmlspecialchars($course['title']) ?></a>
                    </h3>
                    <p class="card-instructor">Instrutor: <?= htmlspecialchars($course['instructor'] ?? '') ?></p>
                    <p class="card-desc"><?= htmlspecialchars($course['short_description'] ?? '') ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/page-bottom.php';
