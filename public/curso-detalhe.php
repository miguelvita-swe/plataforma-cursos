<?php
require_once __DIR__ . '/../includes/courses-functions.php';
require_once __DIR__ . '/../includes/enrollment-functions.php';
require __DIR__ . '/../includes/page-top.php';

// Course retrieval
$courseId = $_GET['id'] ?? '';
$course = null;
if ($courseId !== '') {
    $course = getCourseById((string)$courseId);
}

// Enrollment handling
$enroll_success = false;
$enroll_errors = [];
$email_sent = false;

// include csrf and mail helpers
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/mail-functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'enroll') {
    // Validate CSRF token first
    $posted_token = $_POST['csrf_token'] ?? null;
    if (!validate_csrf_token($posted_token)) {
        $enroll_errors[] = 'Token CSRF inválido. Atualize a página e tente novamente.';
    }

    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));

    if ($name === '') {
        $enroll_errors[] = 'Nome é obrigatório.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $enroll_errors[] = 'E-mail inválido.';
    }
    if (empty($course)) {
        $enroll_errors[] = 'Curso inválido.';
    }

    if (empty($enroll_errors)) {
        // if user is logged in, use their data
        require_once __DIR__ . '/../includes/auth-functions.php';
        $current = auth_user();
        if ($current) {
            $user = get_user_by_id($current['id']);
            $name = $user['name'] ?? $name;
            $email = $user['email'] ?? $email;
        }

        $saved = save_enrollment($name, $email, (string)$courseId);
        if ($saved) {
            // send confirmation email (best-effort)
            $enroll_success = true;
            $sent = send_enrollment_confirmation($email, $name, $course['title'] ?? 'Curso');
            $email_sent = (bool)$sent;
        } else {
            $enroll_errors[] = 'Falha ao salvar inscrição. Tente novamente.';
        }
    }
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
        </div>

        <section class="enroll-section" id="enroll">
            <div class="container">
                <?php if ($enroll_success): ?>
                    <div class="alert-success" style="padding:12px 16px; border-radius:6px; background:#e6ffef; color:#064e3b; margin-bottom:16px;">
                        Inscrição realizada com sucesso!
                        <?php if ($email_sent): ?>
                            <div>Um e-mail de confirmação foi enviado para <strong><?= htmlspecialchars($email) ?></strong>.</div>
                        <?php else: ?>
                            <div>Não foi possível enviar o e-mail automaticamente. Ele foi registrado no log de mensagens.</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($enroll_errors)): ?>
                    <div class="alert-danger" style="margin-bottom:16px;">
                        <ul style="margin:0; padding-left:18px;">
                            <?php foreach ($enroll_errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php // require auth to enroll ?>
                <?php require_once __DIR__ . '/../includes/auth-functions.php'; ?>
                <?php if (!auth_user()): ?>
                    <div style="text-align:center; margin-bottom:16px;">Você precisa <a href="/login.php?return=<?= urlencode($_SERVER['REQUEST_URI']) ?>">entrar</a> ou <a href="/register.php">criar uma conta</a> para se inscrever neste curso.</div>
                <?php else: ?>
                <?php $csrf = get_csrf_token() ?? generate_csrf_token(); ?>
                <form method="post" action="" class="enroll-form" style="max-width:520px; margin:0 auto;">
                    <input type="hidden" name="action" value="enroll" />
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>" />
                    <div style="margin-bottom:12px;">
                        <label for="name">Nome</label>
                        <input id="name" name="name" class="form-input" type="text" value="<?= htmlspecialchars(auth_user()['name'] ?? '') ?>" readonly style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; background:#f7f7f7;" />
                    </div>
                    <div style="margin-bottom:12px;">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" class="form-input" type="email" value="<?= htmlspecialchars(auth_user()['email'] ?? '') ?>" readonly style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; background:#f7f7f7;" />
                    </div>
                    <div style="display:flex; gap:12px; justify-content:center;">
                        <button type="submit" class="btn btn-enroll">Confirmar Inscrição</button>
                        <a href="/minhas-inscricoes.php" class="btn">Ver Minhas Inscrições</a>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </section>
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
