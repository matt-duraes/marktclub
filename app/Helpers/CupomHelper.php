<?php

namespace App\Helpers;

use Http\Request;
use Helpers\TextoHelper;
use Helpers\CurlHelper;

final class CupomHelper
{
    private string $link;
    private string $soucerId;
    private string $token;

    public function __construct(
        private ?Request $request = null
    ) {
        $this->link = env('LOMADEE_LINK', '');
        $this->soucerId = env('LOMADEE_SOURCE_ID', '');
        $this->token = env('LOMADEE_CUPOM_TOKEN', '');
    }

    public function listar(): array
    {
        $param = [
            'sourceId' => $this->soucerId
        ];

        if (!empty($this->request->pesquisa)) {
            $param['keyword'] = $this->request->pesquisa;
        }

        $data = $this->curl('/coupon/_all', $param);

        if ($data['error']) {
            return [];
        }

        return $data['data'];
    }

    public function buscar($id)
    {
        $data = $this->curl('/coupon/_id/' . $id, [
            'sourceId' => $this->soucerId,
        ]);

        if ($data['error']) {
            mensagemStatus(404);
        }

        return $data['data'][0] ?? [];
    }

    private function curl(String $uri, array $data = []): array
    {
        $url = $this->link . '/' . $this->token . $uri;

        if ($data):
            $array = [];
            foreach ($data as $ind => $val):
                $array[] = $ind . '=' . $val;
            endforeach;
            $url .= '?' . implode('&', $array);
        endif;

        $Curl = new CurlHelper();
        $response = $Curl
            ->header([
                'Content-Type' => 'application/json; charset=utf-8;',
            ])->get($url)->array();

        if (is_array($response) && $response) {
            return $this->validarRetorno($response);
        }

        return [
            'error' => true,
        ];
    }

    private function validarRetorno(array $response): array
    {
        if (!isset($response['requestInfo'])
            || !isset($response['requestInfo']['status'])
            || $response['requestInfo']['status'] != 'OK') {
            return ['error' => true];
        }

        $return = [];
        foreach ($response['coupons'] as $r) {
            $validate = new \DateTime(str_replace('/', '-', $r['vigency']));
            $validate = $validate->format('Y-m-d H:i:s');
            if ($validate <= date('Y-m-d H:i:s') || empty($r['store']['name'])) {
                continue;
            }

            $cupom = $r['code'];
            $tipo = 'cupom';
            if ($cupom == 'URL CUPONADA') {
                $tipo = 'link';
                $cupom = '';
            }

            $return[] = [
                'id'        => $r['id'],
                'descricao' => $r['description'],
                'cupom'     => $cupom,
                'tipo'      => $tipo,
                'desconto'  => $r['discount'],
                'parceiro'  => [
                    'id'     => $r['store']['id'],
                    'nome'   => $r['store']['name'],
                    'imagem' => $r['store']['image'],
                    'link'   => $r['store']['link'],
                    'slug'   => (new TextoHelper())->valor($r['store']['name'])->slug('-')->r()
                ],
                'categoria' => [
                    'id'   => $r['category']['id'],
                    'name' => $r['category']['name'],
                ],
                'link'     => $r['link'],
                'validade' => $r['vigency'],
                'novo'     => $r['new'],
            ];
        }

        if (empty($return)) {
            return ['error' => true];
        }

        return [
            'error' => false,
            'data'  => $return,
        ];
    }
}
