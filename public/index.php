<?php
// Tentar incluir Composer autoload se existir
$message = "Bem-vindo à Plataforma de Cursos!";

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    try {
        $hello = new \Application\Hello();
        $message = $hello->message();
    } catch (\Exception $e) {
        // Usar mensagem padrão se classe não existir
    }
}

require __DIR__ . '/../includes/page-top.php';
?>

    <div class="hero-section">
        <h1>Bem-vindo à Plataforma de Cursos</h1>
        <p>Aprenda novos conhecimentos com os melhores instrutores</p>
        <a href="/cursos.php" class="btn" style="background-color: white; color: #0051ba; font-weight: bold;">Explorar Cursos</a>
    </div>

    <div style="text-align: center; margin-bottom: 40px;">
        <h2><?= htmlspecialchars($message) ?></h2>
    </div>

<?php require __DIR__ . '/../includes/page-bottom.php';
