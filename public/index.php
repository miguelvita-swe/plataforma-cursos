<?php
// Carregar dados da plataforma
$message = "Bem-vindo à Plataforma de Cursos UniFio";
$benefits = [];
$stats = [];
$areas = [];

// Tentar incluir classe Hello
if (file_exists(__DIR__ . '/../src/Hello.php')) {
    require_once __DIR__ . '/../src/Hello.php';
    try {
        // Usar métodos estáticos da classe Hello
        $message = \Application\Hello::message();
        $benefits = \Application\Hello::getBenefits();
        $stats = \Application\Hello::getStats();
        $areas = \Application\Hello::getAreas();
    } catch (\Exception $e) {
        // Se houver erro, usar valor padrão
    }
}

require __DIR__ . '/../includes/page-top.php';
?>

    <!-- HERO SECTION -->
    <div class="hero-section">
        <h1><?= htmlspecialchars($message) ?></h1>
        <p>Aprenda novos conhecimentos com os melhores instrutores</p>
        <a href="/cursos.php" class="btn" style="background-color: white; color: #0051ba; font-weight: bold;">Explorar Cursos</a>
    </div>

    <!-- POR QUE USAR UNIFIO? -->
    <section class="benefits-section">
        <div class="section-header">
            <h2>Por que escolher a Plataforma UniFio?</h2>
            <p class="section-subtitle">Descubra os benefícios que tornam nossos cursos únicos</p>
        </div>

        <div class="benefits-grid">
            <?php foreach ($benefits as $index => $benefit): ?>
                <div class="benefit-card" style="animation-delay: <?= $index * 0.1 ?>s;">
                    <div class="benefit-icon" style="background-color: <?= htmlspecialchars($benefit['color']) ?>;">
                        <?= htmlspecialchars($benefit['icon']) ?>
                    </div>
                    <h3><?= htmlspecialchars($benefit['title']) ?></h3>
                    <p><?= htmlspecialchars($benefit['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ESTATÍSTICAS -->
    <?php if (!empty($stats)): ?>
    <section class="stats-section">
        <div class="stats-container">
            <?php foreach ($stats as $index => $stat): ?>
                <div class="stat-item" style="animation-delay: <?= $index * 0.15 ?>s;">
                    <div class="stat-number"><?= htmlspecialchars($stat['number']) ?></div>
                    <div class="stat-label"><?= htmlspecialchars($stat['label']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ÁREAS DE ESTUDO -->
    <?php if (!empty($areas)): ?>
    <section class="areas-section">
        <div class="section-header">
            <h2>Áreas de Estudo</h2>
            <p class="section-subtitle">Escolha a área que mais te interessa</p>
        </div>

        <div class="areas-grid">
            <?php foreach ($areas as $index => $area): ?>
                <a href="/cursos.php?area=<?= urlencode(strtolower($area['name'])) ?>" 
                   class="area-card" 
                   style="border-top-color: <?= htmlspecialchars($area['color']) ?>; animation-delay: <?= $index * 0.15 ?>s;">
                    <div class="area-emoji"><?= htmlspecialchars($area['emoji']) ?></div>
                    <h3><?= htmlspecialchars($area['name']) ?></h3>
                    <p><?= htmlspecialchars($area['courses']) ?> cursos disponíveis</p>
                    <span class="area-arrow">→</span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <h2>Pronto para começar sua jornada?</h2>
        <p>Acesse nosso catálogo completo de cursos e escolha o ideal para você</p>
        <a href="/cursos.php" class="btn btn-large">Acessar Catálogo Completo</a>
    </section>

<?php require __DIR__ . '/../includes/page-bottom.php';
