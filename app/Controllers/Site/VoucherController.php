<?php

namespace App\Controllers\Site;

use Http\Request;
use Controller\Controller;

final class VoucherController extends Controller
{
    public function voucher(Request $request, $url = null)
    {
        $voucher = (object)[
            'id'      => 123,
            'criacao' => '24/02/2023',
            'status'  => 'novo',
            'codigo'  => 'qYiyaf30Qz',
            'voucher' => (object)[
                'titulo' => 'Yes Idiomas',
                'imagem' => (object)[
                    'parceiro'  => 'https://arquivo.marktclub.com.br/parceiro/0009e3aec14f8583fb62a413aa8a9aec.png',
                    'empresa'   => 'https://arquivo.marktclub.com.br/construtor/a2ca966d45780803f2497bd2a77b0e3b.png',
                    'marktclub' => 'https://arquivo.marktclub.com.br/construtor/a2ca966d45780803f2497bd2a77b0e3b.png',
                    'qrcode'    => 'https://chart.apis.google.com/chart?cht=qr&chl=http://voucher.marktclub.com.br/validar/qYiyaf30Qz&chs=300x300'
                ],
                'codigo'  => 'qYiyaf30Qz',
                'usuario' => (object)[
                    'nome' => 'Karina Cruz',
                    'cpf'  => '034.840.871-45',
                ],
                'data' => (object)[
                    'criacao'    => '24/02/2023',
                    'validade'   => '06/03/2023',
                    'vencimento' => '06/03/2023',
                ],
                'texto' => (object)[
                    'desconto' => 'Este convênio possui mais de uma modalidade de desconto, confira quais:
                    - Isenção da taxa de matrícula.
                    - Desconto de 50% nas mensalidades para cursos regulares.

                    Fique atento!
                    - A responsabilidade pela oferta, condições, formas de pagamento e serviços é do YES! Idiomas Asa Sul.
                    - Desconto aplicado somente para pagamentos até a data de vencimento.
                    - Desconto válido apenas para novos alunos.
                    - O desconto não é cumulativo com outras promoções vigentes.',
                    'opcional' => 'Para usar o benefício, basta gerar voucher no botão ao lado e apresentar no ato da matrícula.
                    Não perca tempo, aproveite!',
                    'juridico' => 'Este convênio é administrado pela empresa Markt Tec Serviços em Tecnologia da Informação, CNPJ. 14.150.830/0001-00 - (Markt Club), com contrato firmado no dia 30/10/2017 e sua vigência é por prazo indeterminado. Caso tenha algum problema no ato da utilização, favor entrar em contato pelo meios abaixo:
                        Telefone: 0800 932 0000 ramal 4199 (Apenas telefone fixo) (Apenas telefone fixo)
                        Whatsapp: (61) 99354-6881 (apenas WhatsApp) (apenas WhatsApp)
                        E-mail: atendimento@temmaisvantagens.com.br',
                    'validar' => 'Para validação, acesse voucher.marktclub.com.br ou utilize o QR Code.',
                ],
            ],
        ];

        return view('voucher.loja', [
            'dado' => $voucher
        ]);
    }
}
