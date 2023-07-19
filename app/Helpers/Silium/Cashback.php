<?php

namespace App\Helpers\Silium;

use Erro\Erro;
use Erro\Excecao;
use Helpers\CurlHelper;

class Cashback
{
    private const TRADUCOES_CATEGORIAS = [
        'Cars & Motorbikes'           => 'Carros e Motos',
        'Internet services'           => 'Serviços de Internet',
        'Office & School'             => 'Escolas e escritórios',
        'Family, Sports & Games'      => 'Família, Esportes e Jogos',
        'Computer & Game Consoles'    => 'Computadores e Games',
        'News & Information'          => 'Notícias e Informações',
        'House & Garden'              => 'Casa e Jardinagem',
        'Art & Culture'               => 'Arte e Cultura',
        'Travel & Flight'             => 'Passagens aéreas e pacotes de viagem',
        'Cities, Counrties & Regions' => 'Cidades, Países e Regiões',
        'Books, Music & Movies'       => 'Filmes, Livros e Música',
        'Insurance & Finances'        => 'Seguros e Créditos',
        'Health & Care'               => 'Sáude e Cuidados',
        'Food & Beverages'            => 'Comidas e Bebidas',
        'Gifts & Flowers'             => 'Presentes e Flores ',
        'Clothing & Accessories'      => 'Roupas e Acessórios',
        'Shopping & Mail Order Shops' => 'Compras',
        'Cameras & Camcorders'        => 'Câmeras e Filmadoras',
        'Personal Homepage'           => 'Página Pessoal',
        'HiFi, TV & Video'            => 'Tv, Video, Som e Imagem',
        'Tickets'                     => 'Ingressos',
        'Household Appliances'        => 'Produto para casa ',
        'Cell, Phone & Fax'           => 'Celulares, Telefones e Fax',
        'Other'                       => 'Outros'
    ];

    /**
     * @var string Link da API
     */
    private string $link;

    /**
     * @var string Client Id da API
     */
    private string $clientId;

    /**
     * @var string Secret Id da API
     */
    private string $secretId;

    /**
     * @throws Erro
     */
    public function __construct()
    {
        $envsSilium = [
            'SILIUM_LINK'      => env('SILIUM_LINK'),
            'SILIUM_CLIENT_ID' => env('SILIUM_CLIENT_ID'),
            'SILIUM_SECRET_ID' => env('SILIUM_SECRET_ID')
        ];

        foreach ($envsSilium as $index => $value) {
            if (empty($value)) {
                throw new Erro(
                    "Variável de ambiente $index não foi seta ou está vazia",
                    'Variáveis de Ambiente',
                    "A variável de ambiente $index deve ser preenchida corretamente"
                );
            } elseif (!is_string($value)) {
                throw new Erro(
                    "Esperavamos um valor do tipo STRING na variável de ambiente $index",
                    'Tipagem da variável de ambiente',
                    "A variável de ambiente $index deve ser do tipo STRING"
                );
            }
        }

        $this->link = env('SILIUM_LINK');
        $this->clientId = env('SILIUM_CLIENT_ID');
        $this->secretId = env('SILIUM_SECRET_ID');
    }

    /**
     * @param string $data
     *
     * @return array
     * @throws Excecao
     */
    public function comissao(string $data): array
    {
        $resposta = (new CurlHelper())
            ->get(
                $this->assinarUrl('GET', '/reports/sales/date/' . $data, [
                    'state' => 'confirmed',
                    'items' => 1
                ])
            )
            ->headerJson()
            ->array();

        $id = '@id';
        $cifrao = '$';
        $comissoes = [];
        if ($resposta->total >= 1) {
            foreach ($resposta->saleItems->saleItem as $item) {
                if (!is_object($item) || !object_key_exists('gpps', $item)) {
                    continue;
                }

                $dataApi = $item->trackingDate;
                $dataApi = substr($dataApi, 0, 4)
                    . substr($dataApi, 4, 3)
                    . substr($dataApi, 7, 3)
                    . ' '
                    . substr($dataApi, 11, 8);

                $dataCompra = date('Y-m-d H:i:s', strtotime($dataApi));
                $comissoes[] = (object)[
                    'id_venda'         => $item->$id,
                    'usuario'          => $item->gpps->gpp[0]->$cifrao,
                    'programa'         => $item->program->$id,
                    'valor_compra'     => $item->amount,
                    'moeda'            => $item->currency,
                    'comissao_usuario' => $item->commission,
                    'data_compra'      => $dataCompra,
                ];
            }
        }

        return $comissoes;
    }

    /**
     * @param string $metodo
     * @param string $uri
     * @param array  $dados
     *
     * @return string
     */
    private function assinarUrl(string $metodo, string $uri, array $dados = []): string
    {
        $timestamp = gmdate('D, d M Y H:i:s T', time());
        $nonce = uniqid(more_entropy: true);
        $stringToSign = mb_convert_encoding($metodo . $uri . $timestamp . $nonce, 'UTF-8');
        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $this->secretId, true));

        $query = '';
        if ($metodo === 'GET') {
            foreach ($dados as $query => $value) {
                $query .= '&' . $query . '=' . $value;
            }
        }

        return $this->link . $uri .
            '?connectid=' . $this->clientId .
            '&date=' . urlencode($timestamp) .
            '&nonce=' . $nonce .
            '&signature=' . urlencode($signature) . $query;
    }

    /**
     * @param array $idParceiros
     *
     * @return array
     * @throws Excecao
     */
    public function buscarParceiros(array $idParceiros): array
    {
        $id = '@id';
        $arr = [];
        for ($i = 0; $i < count($idParceiros); $i++) {
            $resposta = (new CurlHelper())
                ->headerJson()
                ->get(
                    $this->assinarUrl('GET', '/programs', [
                        'region' => 'BR',
                        'page'   => $i,
                        'items'  => 50
                    ])
                )
                ->array();

            if (
                !object_key_exists('programItems', $resposta)
                || !object_key_exists('programItem', $resposta->programItems)
            ) {
                continue;
            }

            foreach ($resposta->programItems->programItem as $parceiro) {
                $arr[] = [
                    'cod'               => md5(uniqid(time())),
                    'titulo'            => $parceiro->name,
                    'tipo'              => 1,
                    'empresa'           => $idParceiros[$i],
                    'programa'          => $parceiro->$id,
                    'descricao_publica' => $parceiro->description,
                    'descricao_privada' => $parceiro->descriptionLocal,
                    'categoria'         => $this->pegarTraducao($parceiro->categories),
                    'restricoes'        => '',
                    'comissao_min'      => number_format($parceiro->commission->salePercentMin, 2, '.', ''),
                    'comissao_max'      => number_format($parceiro->commission->salePercentMax, 2, '.', ''),
                    'url'               => strSlug($parceiro->name),
                    'imagem'            => $parceiro->image,
                    'status_api'        => $parceiro->status,
                    'status_clube'      => 3,
                    'data_atualizacao'  => date('Y-m-d H:i:s'),
                ];
            }
        }
        return $arr;
    }

    /**
     * @param array|object $categoria
     *
     * @return array
     */
    private function pegarTraducao(array|object $categoria): array
    {
        $cifrao = '$';
        $traducoes = [];
        if (is_array($categoria[0]->category) && !empty($categoria[0]->category[1])) {
            foreach ($categoria[0]->category as $categoria) {
                $traducoes[] = self::TRADUCOES_CATEGORIAS[$categoria->$cifrao] ?? 'Outros';
            }
        } elseif (!empty($categoria[0]->category->$cifrao)) {
            $traducoes[] = self::TRADUCOES_CATEGORIAS[$categoria[0]->category->$cifrao] ?? 'Outros';
        }

        return $traducoes;
    }
}
