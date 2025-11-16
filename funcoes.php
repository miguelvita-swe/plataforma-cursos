<?php
/**
 * Funções Auxiliares para Renderização da Equipe
 */

/**
 * Renderiza um card de membro da equipe
 * 
 * @param array $membro Array associativo com dados do membro
 * @return void Imprime o HTML do card
 */
function renderizar_card_membro($membro)
{
    $nome = htmlspecialchars($membro['nome'] ?? 'N/A');
    $cargo = htmlspecialchars($membro['cargo'] ?? 'Cargo não especificado');
    $bio = htmlspecialchars($membro['bio'] ?? 'Sem descrição');
    $imagem = htmlspecialchars($membro['imagem'] ?? 'placeholder.jpg');
    $icone = $membro['icone'] ?? '👤';
    
    echo <<<HTML
    <div class="team-card">
        <div class="team-card-image-container">
            <img src="/img/equipe/{$imagem}" alt="{$nome}" class="team-card-image" />
            <div class="team-card-overlay"></div>
        </div>
        <div class="team-card-content">
            <h3 class="team-card-name">{$nome}</h3>
            <p class="team-card-position">
                <span class="position-icon">{$icone}</span>
                {$cargo}
            </p>
            <p class="team-card-description">{$bio}</p>
        </div>
    </div>
HTML;
}

/**
 * Renderiza a seção completa da equipe
 * 
 * @param array $equipe Array contendo todos os membros da equipe
 * @return void Imprime a seção HTML completa
 */
function renderizar_secao_equipe($equipe)
{
    if (empty($equipe)) {
        echo '<p class="alert alert-warning text-center">Nenhum membro de equipe disponível no momento.</p>';
        return;
    }
    
    foreach ($equipe as $membro) {
        renderizar_card_membro($membro);
    }
}
?>
