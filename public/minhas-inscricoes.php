<?php
/**
 * Página: Minhas Inscrições
 * Permite que o usuário consulte suas inscrições por e-mail
 */

require_once __DIR__ . '/../includes/enrollment-functions.php';
require_once __DIR__ . '/../includes/courses-functions.php';
require __DIR__ . '/../includes/page-top.php';

$email = '';
$enrollments = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $enrollments = get_enrollments_by_email($email);
    }
}
?>

<div class="container" style="padding:40px 20px; max-width:900px;">
    <h2>Minhas Inscrições</h2>
    <p>Informe seu e-mail para ver as inscrições associadas.</p>

    <form method="post" style="margin-bottom:22px; display:flex; gap:8px; align-items:center;">
        <input type="email" name="email" placeholder="seu@email.com" required value="<?= htmlspecialchars($email) ?>" style="flex:1; padding:10px; border:1px solid #ddd; border-radius:6px;" />
        <button class="btn" type="submit">Buscar</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php if (empty($enrollments)): ?>
            <div class="alert-danger">Nenhuma inscrição encontrada para este e-mail.</div>
        <?php else: ?>
            <div class="enrollments-list">
                <?php foreach ($enrollments as $enr):
                    $course = getCourseById($enr['course_id']);
                ?>
                    <div class="card" style="margin-bottom:12px; padding:16px;">
                        <h4 style="margin:0 0 6px 0;"><?= htmlspecialchars($course['title'] ?? ('Curso: ' . $enr['course_id'])) ?></h4>
                        <div style="font-size:13px; color:#666; margin-bottom:6px;">Inscrito em: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($enr['created_at'] ?? 'now'))) ?></div>
                        <div style="font-size:14px; color:#333;">Nome: <?= htmlspecialchars($enr['name']) ?> — E-mail: <?= htmlspecialchars($enr['email']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../includes/page-bottom.php';
