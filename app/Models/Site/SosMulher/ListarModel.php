<?php

namespace App\Models\Site\SosMulher;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class ListarModel extends ApiHelper implements ListarInterface
{
    public function __construct()
    {
        parent::__construct(scope: '');
    }

    public function listarDados(): stdClass
    {
        return $this->montarRetorno();
    }

    private function montarRetorno(): stdClass
    {
        return (object)[
            'imagem' => (object)[
                'logo' => 'https://clube.marktclub.com.br/images/denuncia_index_banner_parceiro.png',
                'capa' => 'https://clube.marktclub.com.br/images/denuncia_index_capa_parceiro.png',
            ],
            'descricao' => (object)[
                'titulo'    => 'PRECISA DE AJUDA?',
                'subtitulo' => 'Atenção! Esta página é voltada ao suporte à mulher.
                Em caso de emergência, ligue para o 180'
            ],
            'como_utilizar' => (object)[
                'titulo' => 'Como utilizar?',
                'texto'  => 'Agende uma conversa. Para utilizar essa ferramente,
                basta apertar o botão encontrar parceiros.
                Aqui você localiza empresas que disponibilizam o serviço de atendimento online.'
            ],
            'atendimento' => (object)[
                'titulo' => 'Atendimento online',
                'texto'  => 'Aqui você será redirecionada para o chat do Ministério dos Direitos Humanos.
                Ao entrar em contato por esse campo, você encontra uma forma mais silenciosa e segura de denunciar.'
            ],
            'aviso' => (object)[
                'titulo' => 'AVISO',
                'texto'  => 'Os canais acima não são do Markt Club.
                Você será redirecionado aos órgãos responsáveis pelo atendimento',
            ],
            'denuncie' => (object)[
                'titulo'                    => 'DENUNCIE AQUI',
                'disque_atendimento_mulher' => 'https://clube.marktclub.com.br/images/denuncia_index_banner_180.png',
                'disque_direito_humano'     => 'https://clube.marktclub.com.br/images/denuncia_index_banner_100.png'
            ],
            'contato' => (object)[
                'botao_site' => (object)[
                    'texto' => 'ENCONTRAR PARCEIROS',
                    'link'  => [
                        'pesquisa' => 'terapia',
                        'ordem'    => 'novo'
                    ]
                ],
                'botao_telefone' => (object)[
                    'texto' => 'Ligar',
                    'valor' => ['180']
                ],
                'botao_chat' => (object)[
                    'texto' => 'Chat',
                    'link'  => 'https://mdh-chat.metasix.solutions/livechat?mode=popout'
                ]
            ]
        ];
    }

    public function listarRelacionado(): stdClass
    {
        return (object)[
            'tipo'  => 'sosmulher',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => '180',
                    'link'   => '',
                    'imagem' => 'https://clube.marktclub.com.br/images/denuncia_index_banner_180.png'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => '100',
                    'link'   => '',
                    'imagem' => 'https://clube.marktclub.com.br/images/denuncia_index_banner_100.png'
                ],
            ]
        ];
    }
}
