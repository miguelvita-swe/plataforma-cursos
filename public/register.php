<?php
/**
 * Registro de Usuário
 */
require_once __DIR__ . '/../includes/auth-functions.php';
require __DIR__ . '/../includes/page-top.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $password2 = (string)($_POST['password2'] ?? '');
    $bio = trim((string)($_POST['bio'] ?? ''));

    if ($name === '') $errors[] = 'Nome é obrigatório.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
    if ($password === '' || strlen($password) < 6) $errors[] = 'Senha deve ter ao menos 6 caracteres.';
    if ($password !== $password2) $errors[] = 'As senhas não conferem.';
    if (find_user_by_email($email) !== null) $errors[] = 'E-mail já cadastrado.';

    // handle image upload
    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['image']['tmp_name'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('usr_', true) . '.' . $ext;
        $dest = __DIR__ . '/img/users/' . $imageName;
        move_uploaded_file($tmp, $dest);
    }

    if (empty($errors)) {
        $user = create_user($name, $email, $password, $bio, $imageName);
        if ($user) {
            auth_login_user($user);
            header('Location: /profile.php');
            exit;
        } else {
            $errors[] = 'Erro ao criar usuário.';
        }
    }
}
?>

<div class="container" style="padding:40px 20px; max-width:700px;">
    <h2>Cadastro</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert-danger"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Nome</label>
        <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" />

        <label>E-mail</label>
        <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />

        <label>Senha</label>
        <input type="password" name="password" required />

        <label>Confirmar Senha</label>
        <input type="password" name="password2" required />

        <label>Biografia (opcional)</label>
        <textarea name="bio"><?= htmlspecialchars($_POST['bio'] ?? '') ?></textarea>

        <label>Foto de Perfil (opcional)</label>
        <input type="file" name="image" accept="image/*" />

        <div style="margin-top:12px;"><button class="btn" type="submit">Registrar</button></div>
    </form>
</div>

<?php require __DIR__ . '/../includes/page-bottom.php';
