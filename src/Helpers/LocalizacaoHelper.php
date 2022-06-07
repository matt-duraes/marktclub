<?php

namespace Helpers;

final class LocalizacaoHelper
{
    /**
     * Busca os dados geograficos pelo IP do usuário
     *
     * @param null|string $ip IP do usuário ou null para tentar pegar IP automático
     * @return array
     */
    public function geoip(?string $ip = null): array
    {
        $ip = !empty($ip) ? $ip : ip();
        $url = (string) "http://ip-api.com/json/{$ip}?fields=65535";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $retorno = jsonDecode(curl_exec($ch), true);
        if (existeErro($retorno, 'status') || $retorno['status'] != 'success') {
            return [
                'status' => 'erro',
                'erro' => [
                    'titulo' => 'Erro na API',
                    'mensagem' => 'Não foi possível conectar com a API de localização.',
                    'codigo' => 500
                ]
            ];
        }

        return [
            'status' => 'sucesso',
            'dado' => [
                'pais' => isset($retorno['countryCode']) ? $retorno['countryCode'] : '',
                'estado' => isset($retorno['region']) ? $retorno['region'] : '',
                'cidade' => isset($retorno['city']) ? $retorno['city'] : '',
                'latitude' => isset($retorno['lat']) ? $retorno['lat'] : '',
                'longitude' => isset($retorno['lon']) ? $retorno['lon'] : '',
                'provedor' => isset($retorno['isp']) ? $retorno['isp'] : '',
            ]
        ];
    }

    // private $tipo;
    // private $endereco;
    // private $latitude;
    // private $longitude;
    // private $key;
    // private $cep;
    // private $estadoId = ['RO' => 11, 'AC' => 12, 'AM' => 13, 'RR' => 14, 'PA' => 15, 'AP' => 16, 'TO' => 17, 'MA' => 21, 'PI' => 22, 'CE' => 23, 'RN' => 24, 'PB' => 25, 'PE' => 26, 'AL' => 27, 'SE' => 28, 'BA' => 29, 'MG' => 31, 'ES' => 32, 'RJ' => 33, 'SP' => 35, 'PR' => 41, 'SC' => 42, 'RS' => 43, 'MS' => 50, 'MT' => 51, 'GO' => 52, 'DF' => 53];

    // public function __construct()
    // {
    //     $this->key = '';
    //     $this->tipo = '';
    // }

    // public function buscarEnderecoPeloCep()
    // {
    //     if (empty($this->cep)) {
    //         return (object) ['erro' => true];
    //     }

    //     $ch = curl_init('https://brasilapi.com.br/api/cep/v1/' . $this->cep);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    //     $retorno = jsonDecode(curl_exec($ch), true);

    //     if ($retorno && is_array($retorno) && isset($retorno['cep'])) {
    //         return (object) [
    //             'erro' => false,
    //             'bairro' => $retorno['neighborhood'] ?? '',
    //             'logradouro' => $retorno['street'] ?? '',
    //             'cidade' => $retorno['city'] ?? '',
    //             'estado' => $retorno['state'] ?? '',
    //             'cep' => $retorno['cep'] ?? '',
    //             'pais' => isset($retorno['state']) && !empty($retorno['state']) ? 'BR' : '',
    //         ];
    //     }

    //     return (object) ['erro' => true];
    // }

    // public function cep($cep)
    // {
    //     $this->tipo = 'cep';
    //     $this->cep = preg_replace('/[^0-9]/', '', $cep);

    //     return $this;
    // }

    // public function endereco($endereco = '')
    // {
    //     if (empty($this->tipo)) {
    //         $this->tipo = 'endereco';
    //     }

    //     if ($this->tipo == 'endereco') {
    //         $this->endereco = $endereco;
    //         return $this;
    //     } elseif ($this->tipo == 'geolocalizacao') {
    //         $this->tipo = '';
    //         return $this->buscarEnderecoPelaGeolocalizacao();
    //     } elseif ($this->tipo == 'cep') {
    //         $this->tipo = '';
    //         return $this->buscarEnderecoPeloCep();
    //     }
    // }

    // private function buscarEnderecoPelaGeolocalizacao()
    // {
    //     if (empty($this->latitude) || empty($this->longitude)) {
    //         return mensagemErro('Campo obrigatório!', 'Você deve passar a latitude e longitude', 400);
    //     }

    //     $url = 'https://maps.google.com/maps/api/geocode/json?latlng=' . $this->latitude . ',' . $this->longitude . '&key=' . $this->key;

    //     $ch = curl_init($url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

    //     $retorno = jsonDecode(curl_exec($ch), true);

    //     if (!isset($retorno['results']) || empty($retorno['results'])) {
    //         return [
    //             'erro' => false,
    //             'statusHtml' => 404,
    //         ];
    //     }

    //     $endereco = [];

    //     foreach ($retorno['results'] as $r) {
    //         if ($r['geometry']['location']['lat'] == $this->latitude && $r['geometry']['location']['lng'] == $this->longitude) {
    //             $endereco = $r['address_components'];

    //             break;
    //         } elseif (empty($enderecoCompleto)) {
    //             $endereco = $r['address_components'];
    //         }
    //     }

    //     // return $this->montarEndereco($endereco);
    // }

    // public function geolocalizacao($latitude = '', $longitude = '')
    // {
    //     if (empty($this->tipo)) {
    //         $this->tipo = 'geolocalizacao';
    //     }

    //     if ($this->tipo == 'geolocalizacao') {
    //         $this->latitude = $latitude;
    //         $this->longitude = $longitude;

    //         return $this;
    //     } elseif ($this->tipo == 'endereco') {
    //         $this->tipo = '';
    //         return $this->buscarGeolocalizacaoPeloEndereco();
    //     }
    // }

    // private function buscarGeolocalizacaoPeloEndereco()
    // {
    //     if (empty($this->endereco)) {
    //         return mensagemErro('Campo obrigatório!', 'Você deve passar um endereço.', 400);
    //     }

    //     $endereco = $this->endereco;

    //     $url = 'https://maps.google.com/maps/api/geocode/json?address=' . urlencode($endereco) . '&key=' . $this->key . '&region=BR&components=country:BR|language:pt-BR';

    //     $ch = curl_init($url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

    //     $retorno = jsonDecode(curl_exec($ch), true);

    //     if (!isset($retorno['results'][0]['formatted_address'])) {
    //         $url = 'https://maps.google.com/maps/api/geocode/json?address=' . urlencode($endereco) . ',&key=' . $this->key . '&sensor=false&components=language:pt-BR';

    //         $ch = curl_init($url);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

    //         $retorno = jsonDecode(curl_exec($ch), true);
    //     }

    //     if (!isset($retorno['results'][0]['formatted_address'])) {
    //         return [
    //             'erro' => false,
    //             'statusHtml' => 404,
    //         ];
    //     }

    //     $endereco = $this->montarLogradouro($retorno['results'][0]['address_components']);

    //     return [
    //         'erro' => false,
    //         'endereco' => $endereco['completo'] ?? '',
    //         'latitude' => $retorno['results'][0]['geometry']['location']['lat'],
    //         'longitude' => $retorno['results'][0]['geometry']['location']['lng'],
    //     ];
    // }

    // private function montarLogradouro($dado)
    // {
    //     $enderecoLogradouro = [];
    //     $enderecoCidade = '';
    //     $enderecoEstado = '';
    //     $enderecoPais = [];
    //     $enderecoCep = '';

    //     if ($dado) {
    //         foreach ($dado as $r) {
    //             if (in_array('country', $r['types'])) {
    //                 $enderecoPais = [$r['short_name'], $r['long_name']];
    //             } elseif (in_array('administrative_area_level_1', $r['types'])) {
    //                 $enderecoEstado = $r['short_name'];
    //             } elseif (in_array('administrative_area_level_2', $r['types'])) {
    //                 $enderecoCidade = $r['long_name'];
    //             } elseif (in_array('postal_code', $r['types'])) {
    //                 $enderecoCep = $r['long_name'];
    //             } else {
    //                 $enderecoLogradouro[] = $r['long_name'];
    //             }
    //         }
    //     }

    //     $enderecoLogradouro = implode(', ', $enderecoLogradouro);
    //     if ($enderecoLogradouro == $enderecoCidade) {
    //         $enderecoLogradouro = '';
    //     }

    //     $enderecoCompleto = $enderecoLogradouro;

    //     if (!empty($enderecoCompleto) && !empty($enderecoCidade)) {
    //         $enderecoCompleto .= ' - ' . $enderecoCidade;
    //     } elseif (!empty($enderecoCidade)) {
    //         $enderecoCompleto = $enderecoCidade;
    //     }

    //     if (!empty($enderecoCidade) && !empty($enderecoEstado)) {
    //         $enderecoCompleto .= '/' . $enderecoEstado;
    //     } elseif (!empty($enderecoCompleto) && !empty($enderecoEstado)) {
    //         $enderecoCompleto .= ' - ' . $enderecoEstado;
    //     }

    //     if (!empty($enderecoCompleto) && !empty($enderecoCep)) {
    //         $enderecoCompleto .= ' - CEP: ' . $enderecoCep;
    //     }

    //     if (!empty($enderecoCompleto) && isset($enderecoPais[1]) && !empty($enderecoPais[1])) {
    //         $pais = [
    //             'Germany' => 'Alemanha',
    //             'Belgium' => 'Bélgica',
    //             'Australia' => 'Austrália',
    //             'Bolivia' => 'Bolívia',
    //             'Brazil' => 'Brasil',
    //             'Canada' => 'Canadá',
    //             'Colombia' => 'Colômbia',
    //             'Korea' => 'Coréia',
    //             'Ecuador' => 'Equador',
    //             'Spain' => 'Espanha',
    //             'The United States of America' => 'EUA',
    //             'Denmark' => 'Dinamarca',
    //             'France' => 'França',
    //             'Greece' => 'Grécia',
    //             'England' => 'Inglaterra',
    //             'Italy' => 'Itália',
    //             'Japan' => 'Japão',
    //             'Norway' => 'Noruega',
    //             'Paraguay' => 'Paraguai',
    //             'México' => 'Mexico',
    //             'Peru' => 'Perú',
    //             'Russia' => 'Rússia',
    //             'Sweden' => 'Suécia',
    //             'Uruguay' => 'Uruguai',
    //             'Iran' => 'Irã',
    //             'Iraq' => 'Iraque',
    //             'Egypt' => 'Egito',
    //             'Turkey' => 'Turquia',
    //             'Thailand' => 'Tailândia',
    //         ];
    //         $enderecoCompleto .= ' - ' . str_replace(array_keys($pais), array_values($pais), $enderecoPais[1]);
    //     }

    //     return [
    //         'erro' => false,
    //         'completo' => $enderecoCompleto,
    //         'cep' => preg_replace('/[^0-9]/', '', $enderecoCep),
    //         'logradouro' => $enderecoLogradouro,
    //         'cidade' => $enderecoCidade,
    //         'estado' => $enderecoEstado,
    //         'pais' => $enderecoPais[0] ?? '',
    //     ];
    // }

    // public function cidade(string $estado): array
    // {
    //     $id = $this->estadoId[$estado] ?? false;
    //     if (false) {
    //         return [];
    //     }

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $id . '/municipios');
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     $dado = jsonDecode(curl_exec($ch), false);
    //     if (!is_array($dado) || !isset($dado[0]) || !isset($dado[0]->id)) {
    //         return [];
    //     }

    //     $array = [];
    //     foreach ($dado as $r) {
    //         $array[$r->nome] = $r->nome;
    //     }
    //     return $array;
    // }
}
