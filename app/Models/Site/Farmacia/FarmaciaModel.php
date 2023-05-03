<?php

namespace App\Models\Site\Farmacia;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class FarmaciaModel extends ApiHelper implements ListarInterface
{
    public function __construct()
    {
        parent::__construct(scope: '');
    }

    public function listarDados(): stdClass
    {
        return $this->montarRetorno();
    }

    public function buscarFarmacia(string $url): stdClass
    {
        foreach ($this->montarRetorno()->lista as $farmacia) {
            if ($farmacia->titulo === $url) {

                $farmacia->texto_desconto = nl2br($farmacia->texto_desconto);
                $farmacia->texto_desconto= str_replace('\n', "", $farmacia->texto_desconto);
                return $farmacia;
            }
        }
    }
    private function montarRetorno(): stdClass
    {
        return (object)[
            'tipo'  => 'farmacia',
            'lista' => [
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'PagueMenos',
                    'link'     => route('farmacia.detalhe').'/PagueMenos',
                    'imagem'   => LINK_PADRAO . '/images/site/drogaraia.png',
                    'texto_descricao' =>'Para este convênio é utilizado carteirinha no estabelecimento. E este conta com mais de uma modalidade de desconto, confira quais:',
                    'texto_desconto' =>"- Mínimo garantido de 27% em Genéricos;<br>
                        - Mínimo garantido de 16% em medicamentos tarjados;<br>
                        Fique atento!<br>
                        - A responsabilidade pela oferta, condições, formas de pagamento, produtos e entrega é da Droga Raia;<br>
                        - O desconto não é cumulativo com outras promoções vigentes, caso algum produto esteja em oferta ou queima de estoque, sempre prevalecerá o maior desconto.<br>
                        - Promoção exclusiva loja física.",
                    'texto_procedimento' => '- Apresentar carteirinha " TEM+SAÚDE" para o ATENDENTE no balcão, informe que possui convênio com a rede Droga Raia;
                        - Solicite ao atendente para localizar no PDV em tipo de cliente “Convênio e Parceria”;
                        - Em seguida, selecionar a empresa "TEMMAISSAÚDE– Markt Club";
                        - O atendente irá solicitar o número do seu CPF para confirmação de vínculo;
                        - O número de CPF é o codigo identificador e deve ser preenchido tanto no campo de nº de identificação como no do nº do CPF.
                        - Informe o número do CPF somente após o atendente localizar o nome "TEMMAISSAÚDE– Markt Club".
                        - O atendente irá finalizar o cadastro e liberar o desconto.
                        Não perca tempo, aproveite e boas compras!
                        * Para verificar endereços e contatos clique em "acessar site" no botão abaixo.
                        ',
                    'link_site' => 'https://www.paguemenos.com.br/nossas-lojas'
                ],
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'Drogasil',
                    'link'     => route('farmacia.detalhe').'/Drogasil',
                    'imagem'   => LINK_PADRAO . '/images/site/drogaraia.png',
                    'texto_descricao' =>'',
                    'texto_desconto' =>'- Mínimo garantido de 27% em Genéricos;\n
                    - Mínimo garantido de 16% em medicamentos tarjados;\n
                        Fique atento!\n
                        - A responsabilidade pela oferta, condições, formas de pagamento, produtos e entrega é da Droga Raia;\n
                        - O desconto não é cumulativo com outras promoções vigentes, caso algum produto esteja em oferta ou queima de estoque, sempre prevalecerá o maior desconto.\n
                        - Promoção exclusiva loja física.',
                    'texto_procedimento' => '',
                    'link_site' => ''
                ],
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'Raia',
                    'link'     => route('farmacia.detalhe') . '/Raia',
                    'imagem'   => LINK_PADRAO . '/images/site/drogaraia.png',
                    'texto_descricao' =>'',
                    'texto_desconto' =>'- Mínimo garantido de 27% em Genéricos;\n
                    - Mínimo garantido de 16% em medicamentos tarjados;\n
                        Fique atento!\n
                        - A responsabilidade pela oferta, condições, formas de pagamento, produtos e entrega é da Droga Raia;\n
                        - O desconto não é cumulativo com outras promoções vigentes, caso algum produto esteja em oferta ou queima de estoque, sempre prevalecerá o maior desconto.\n
                        - Promoção exclusiva loja física.',
                    'texto_procedimento' => '',
                    'link_site' => ''
                ]

            ]
        ];
    }
}
