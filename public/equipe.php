<?php
require __DIR__ . '/../includes/page-top.php';
require_once __DIR__ . '/../dados.php';
require_once __DIR__ . '/../funcoes.php';
?>

<div class="team-section">
    <div class="team-container">
        <div class="team-header">
            <span class="team-subtitle">NOSSA EQUIPE</span>
            <h1 class="team-title">Com a nossa equipe, você pode esperar atendimento personalizado e designs envolventes.</h1>
            <p class="team-description">Conheça os profissionais talentosos que trabalham para levar a melhor experiência de aprendizado.</p>
        </div>

        <div class="team-grid">
            <?php renderizar_secao_equipe($equipe); ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/page-bottom.php';
