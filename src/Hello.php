<?php
namespace Application;

class Hello {
    public static function message() : string {
        return "Bem-vindo à Plataforma de Cursos UniFio";
    }

    /**
     * Retorna os benefícios da plataforma em formato estruturado
     * @return array Array com benefícios e características
     */
    public static function getBenefits() : array {
        return [
            [
                'icon' => '🎯',
                'title' => 'Aprendizado Personalizado',
                'description' => 'Cursos adaptados às suas necessidades e ritmo de evolução.',
                'color' => '#FF6B35'
            ],
            [
                'icon' => '👨‍🏫',
                'title' => 'Instrutores Experientes',
                'description' => 'Profissionais qualificados e avaliados por centenas de alunos.',
                'color' => '#004E89'
            ],
            [
                'icon' => '🔒',
                'title' => 'Ambiente Seguro',
                'description' => 'Plataforma confiável, pagamentos protegidos e suporte dedicado.',
                'color' => '#00A676'
            ],
            [
                'icon' => '📚',
                'title' => 'Conteúdo Dinâmico',
                'description' => 'Materiais atualizados regularmente com as tendências do mercado.',
                'color' => '#9D4EDD'
            ],
            [
                'icon' => '🚀',
                'title' => 'Acelerador de Carreira',
                'description' => 'Adquira habilidades demandadas pelas melhores empresas.',
                'color' => '#FF006E'
            ],
            [
                'icon' => '🏆',
                'title' => 'Certificação Reconhecida',
                'description' => 'Obtenha certificados reconhecidos no mercado profissional.',
                'color' => '#3A86FF'
            ]
        ];
    }

    /**
     * Retorna estatísticas da plataforma
     * @return array Array com números e estatísticas
     */
    public static function getStats() : array {
        return [
            ['number' => '1000+', 'label' => 'Alunos Satisfeitos'],
            ['number' => '25+', 'label' => 'Cursos Disponíveis'],
            ['number' => '95%', 'label' => 'Taxa de Conclusão'],
            ['number' => '4.9★', 'label' => 'Avaliação Média']
        ];
    }

    /**
     * Retorna áreas de estudo disponíveis
     * @return array Array com áreas e informações
     */
    public static function getAreas() : array {
        return [
            [
                'name' => 'Programação',
                'emoji' => '💻',
                'courses' => 8,
                'color' => '#0051BA'
            ],
            [
                'name' => 'Design',
                'emoji' => '🎨',
                'courses' => 6,
                'color' => '#FF2D9F'
            ],
            [
                'name' => 'Negócios',
                'emoji' => '📊',
                'courses' => 5,
                'color' => '#FF8C00'
            ]
        ];
    }
}