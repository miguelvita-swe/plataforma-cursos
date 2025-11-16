<?php
/**
 * Login
 */
require_once __DIR__ . '/../includes/auth-functions.php';
require __DIR__ . '/../includes/page-top.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
    if ($password === '') $errors[] = 'Senha é obrigatória.';

    if (empty($errors)) {
        $user = verify_user_credentials($email, $password);
        if ($user) {
            auth_login_user($user);
            $return = $_GET['return'] ?? '/';
            header('Location: ' . $return);
            exit;
        } else {
            $errors[] = 'E-mail ou senha incorretos.';
        }
    }
}
?>

<div class="container" style="padding:40px 20px; max-width:600px;">
    <h2>Entrar</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert-danger"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="post">
        <label>E-mail</label>
        <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />

        <label>Senha</label>
        <input type="password" name="password" required />

        <div style="margin-top:12px; display:flex; gap:8px;">
            <button class="btn" type="submit">Entrar</button>
            <a href="/register.php" class="btn">Criar Conta</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../includes/page-bottom.php';
