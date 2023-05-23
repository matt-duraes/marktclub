<?php

namespace App\Models\Site\Loja;

use stdClass;

trait MontarRetornoDetalheTrait
{
    public function __construct()
    {
        parent::__construct(scope: '');
    }

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

    private function montarSalaVip(): stdClass
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
                'titulo' => 'SALA VIP DO AEROPORTO INTERNACIONAL DE BRASÍLIA - PRESIDENTE JUSCELINO KUBITSCHEK',
                'texto' => (object)[
                    'descricao' => 'Um espaço de 1.500 m² com serviços exclusivos e atendimento de primeira classe.',
                    'desconto' => 'Para este convênio é utilizado voucher no estabelecimento.
                    E este conta com mais de uma modalidade de desconto, confira quais:

                    Fique atento!
                    - A responsabilidade pela oferta, condições, formas de pagamento e produtos é do(a)
                    Sala Vip Aeroporto Internacional Juscelino Kubitschek;
                    - O associado TITULAR terá o direito de acessar SEM CUSTOS na primeira utilização do mês;
                    - A partir da segunda utilização terá desconto de 50% na tarifa vigente da SALA VIP
                    DO AEROPORTO INTERNACIONAL DE BRASÍLIA - PRESIDENTE JUSCELINO KUBITSCHEK;
                    - Válido para Sala Vip Doméstica, Express e Internacional.
                    - O desconto não é cumulativo com outras promoções vigentes.
                    - Promoção exclusiva de loja física.

                    *Importante:
                    - Os custos advindos desta utilização serão de responsabilidade exclusiva da
                    ANAFE - ASSOCIAÇÃO NACIONAL DOS ADVOGADOS PÚBLICOS FEDERAIS. Sendo assim,
                    o associado TITULAR não deverá pagar nenhuma taxa à Inframérica;
                    - Para cada DEPENDENTE, o desconto a ser pago será no valor de 50% da tarifa vigente, no ato da utilização.',
                    'procedimento' => 'Para usar o benefício, basta clicar em "Imprimir Voucher" e apresentar no ato da utilização.',
                ],
                'imagem' => (object)[
                    'logo' => 'https://arquivo.marktclub.com.br/parceiro/a82055f1446026f37fda574d337fcf06.png',
                    'capa' => 'https://arquivo.marktclub.com.br/parceiro/a9e26316b4ecec7fad6883f8ad48ca23.jpg',
                ],
                'categoria' => 'outros',
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
