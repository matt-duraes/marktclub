<?php

namespace App\Models\Site\Loja;

use stdClass;

trait MontarRetornoDetalheTrait
{
    private function montarRetorno(): stdClass
    {
        $voucher = [
            'erro' => false,
            'confirmar' => false,
            'titulo' => '',
            'texto' => '',
        ];
        $siteBotaoTexto = 1;

        $siteTexto = 'ABRIR SITE';
        $carteirinhaMedicamento = 2;
        if ($siteBotaoTexto == 2) :
            $siteTexto = 'GERAR CARTEIRINHA';
            $carteirinhaMedicamento = 1;
        endif;

        $site = 'https://marktclub.com.br';


        return (object)[
            'tipo'  => 'loja',
            'lista' => (object)[
                'id' => uuid(),
                'titulo' => 'Nome da empresa',
                'texto' => (object)[
                    'descricao' => 'Doutor acesso é um aplicativo de telemdicina exclusiva
                    para consultas on-line. Tenha médicos 24 horas por dia e mais de 20
                    especialidades médicos por agendamento. Baixe o app, se consulte em
                    10min e pague só daqui 7 dias',
                    'desconto' => 'Desconto de 25% vitalicio sem carência, no serviço
                    de telemedicina da Doutor Acesso Fideliadde de apenas 6 meses.',
                    'procedimento' => 'Desconto de 25% vitalicio sem carência, no
                    serviço de telemedicina da Doutor Acesso Fideliadde de apenas 6 meses.',
                ],
                'imagem' => (object)[
                    'logo' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'capa' => 'https://clube.marktclub.com.br/images/tem_mais_saude_carteirinha.png',
                ],
                'categoria' => 'saude',
                'procedimento' => 'voucher',
                'voucher' => $voucher,
                'url' => 'loja',
                'cashback' => '',
                'favorito' => false,
                'arquivo' => (object)[],
                'contato' => (object)[
                    'site' => $site ? $site : '',
                    'website' => (object)[
                        'link' => $site ? $site : '',
                        'texto' => $siteTexto,
                    ],
                    'telefone' => '(61) 9999-9999',
                    'whatsapp' => '(61) 9999-9999',
                    'email' => 'ti@markt.club',
                    'endereco' => 'SIA TRECHO 3',
                ],
                'carteirinha_medicamento' => $carteirinhaMedicamento
            ]
        ];
    }
}
