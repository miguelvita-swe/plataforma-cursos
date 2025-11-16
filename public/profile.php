<?php
/**
 * Profile page - view and edit
 */
require_once __DIR__ . '/../includes/auth-functions.php';
require_once __DIR__ . '/../includes/auth-functions.php';
require __DIR__ . '/../includes/page-top.php';

$user = auth_user();
if (!$user) {
    header('Location: /login.php?return=/profile.php');
    exit;
}

$full = get_user_by_id($user['id']);
$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $name = trim((string)($_POST['name'] ?? ''));
    $bio = trim((string)($_POST['bio'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($name === '') $errors[] = 'Nome é obrigatório.';

    $imageName = $full['image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['image']['tmp_name'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('usr_', true) . '.' . $ext;
        $dest = __DIR__ . '/img/users/' . $imageName;
        move_uploaded_file($tmp, $dest);
    }

    $data = [
        'name' => $name,
        'bio' => $bio,
        'image' => $imageName
    ];
    if ($password !== '') $data['password'] = $password;

    $updated = update_user($full['id'], $data);
    if ($updated) {
        // update session info
        auth_login_user($updated);
        $full = $updated;
        $success = true;
    } else {
        $errors[] = 'Falha ao atualizar perfil.';
    }
}
?>

<div class="container" style="padding:40px 20px; max-width:900px;">
    <h2>Meu Perfil</h2>

    <?php if ($success): ?>
        <div class="alert-success">Perfil atualizado com sucesso.</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert-danger"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <div style="display:flex; gap:24px; align-items:flex-start;">
        <div style="width:220px;">
            <?php if (!empty($full['image'])): ?>
                <img src="/img/users/<?= htmlspecialchars($full['image']) ?>" alt="<?= htmlspecialchars($full['name']) ?>" style="width:100%; border-radius:8px;" />
            <?php else: ?>
                <div style="width:100%; height:220px; background:#f0f0f0; border-radius:8px; display:flex; align-items:center; justify-content:center;">Sem foto</div>
            <?php endif; ?>
        </div>

        <form method="post" enctype="multipart/form-data" style="flex:1;">
            <input type="hidden" name="action" value="update" />
            <label>Nome</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($full['name'] ?? '') ?>" />

            <label>Biografia</label>
            <textarea name="bio"><?= htmlspecialchars($full['bio'] ?? '') ?></textarea>

            <label>Alterar foto</label>
            <input type="file" name="image" accept="image/*" />

            <label>Nova senha (opcional)</label>
            <input type="password" name="password" />

            <div style="margin-top:12px;"><button class="btn" type="submit">Salvar</button> <a class="btn" href="/logout.php">Sair</a></div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/page-bottom.php';
