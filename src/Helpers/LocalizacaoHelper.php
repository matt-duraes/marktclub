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
    public function pegarDadosPeloIp(?string $ip = null): array
    {
        $ip = !empty($ip) ? $ip : ip();
        $url = (string) "http://ip-api.com/json/{$ip}?fields=65535";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $retorno = jsonDecode(curl_exec($ch), true);

        if (existeErro($retorno, 'status') || $retorno['status'] != 'success') {
            $this->mensagemErroApi();
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

    public function pegarEnderecoPeloCep(int $cep)
    {
        $ch = curl_init('https://brasilapi.com.br/api/cep/v1/' . $cep);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $retorno = jsonDecode(curl_exec($ch), true);
        if (!is_array($retorno) || !array_key_exists('cep', $retorno)) {
            $this->mensagemErroApi();
        }
        return (object) [
            'status' => 'sucesso',
            'dado' => [
                'logradouro' => $retorno['street'] ?? '',
                'bairro' => $retorno['neighborhood'] ?? '',
                'cidade' => $retorno['city'] ?? '',
                'estado' => $retorno['state'] ?? '',
                'cep' => array_key_exists('cep', $retorno) && !empty($retorno['cep']) ? soNumero($retorno['cep']) : '',
                'pais' => array_key_exists('state', $retorno) && !empty($retorno['state']) ? 'BR' : '',
            ]
        ];
    }



    public function pegarEnderecoPelaGeolocalizacao($latitude, $longitude)
    {
        $url = 'https://maps.google.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&key=' . $this->googleKey();

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $retorno = jsonDecode(curl_exec($ch), true);

        if (!isset($retorno['results']) || empty($retorno['results'])) {
            $this->mensagemErroApi();
        }

        $endereco = [
            'bairro' => '',
            'cidade' => '',
            'estado' => '',
            'cep' => '',
            'pais' => '',
        ];

        foreach ($retorno['results'] as $r) {
            if ($r['geometry']['location']['lat'] == $latitude && $r['geometry']['location']['lng'] == $longitude) {
                foreach ($r['address_components'] as $campo) {
                    if (!array_key_exists('types', $campo) || !array_key_exists('short_name', $campo)) {
                        continue;
                    } else if (in_array('administrative_area_level_4', $campo['types'])) {
                        $endereco['bairro'] = $campo['short_name'];
                    } else if (in_array('administrative_area_level_2', $campo['types'])) {
                        $endereco['cidade'] = $campo['short_name'];
                    } else if (in_array('administrative_area_level_1', $campo['types'])) {
                        $endereco['estado'] = $campo['short_name'];
                    } else if (in_array('country', $campo['types'])) {
                        $endereco['pais'] = $campo['short_name'];
                    } else if (in_array('postal_code', $campo['types'])) {
                        $endereco['cep'] = soNumero($campo['short_name']);
                    }
                }
                break;
            }
        }

        return [
            'status' => 'sucesso',
            'dado' => $endereco
        ];
    }

    public function pegarGeolocalizacaoPeloEndereco(string $endereco)
    {
        $url = 'https://maps.google.com/maps/api/geocode/json?address=' . urlencode($endereco) . '&key=' . $this->googleKey() . '&region=BR&components=country:BR|language:pt-BR';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $retorno = jsonDecode(curl_exec($ch), true);

        if (!$this->validarRetornoGoogle($retorno)) {
            $url = 'https://maps.google.com/maps/api/geocode/json?address=' . urlencode($endereco) . ',&key=' . $this->googleKey() . '&sensor=false&components=language:pt-BR';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

            $retorno = jsonDecode(curl_exec($ch), true);
        }

        if (!$this->validarRetornoGoogle($retorno)) {
            $this->mensagemErroApi();
        }

        return [
            'status' => 'sucesso',
            'dado' => [
                'latitude' => $retorno['results'][0]['geometry']['location']['lat'],
                'longitude' => $retorno['results'][0]['geometry']['location']['lng'],
            ]
        ];
    }
    private function validarRetornoGoogle($retorno)
    {
        return is_array($retorno) &&
            array_key_exists('results', $retorno) &&
            array_key_exists(0, $retorno['results']) &&
            array_key_exists('formatted_address', $retorno['results'][0]);
    }

    public function pegarListaCidadePeloEstado(string $estado): array
    {
        $id = [
            'RO' => 11, 'AC' => 12, 'AM' => 13, 'RR' => 14, 'PA' => 15, 'AP' => 16, 'TO' => 17, 'MA' => 21,
            'PI' => 22, 'CE' => 23, 'RN' => 24, 'PB' => 25, 'PE' => 26, 'AL' => 27, 'SE' => 28, 'BA' => 29,
            'MG' => 31, 'ES' => 32, 'RJ' => 33, 'SP' => 35, 'PR' => 41, 'SC' => 42, 'RS' => 43, 'MS' => 50,
            'MT' => 51, 'GO' => 52, 'DF' => 53
        ][strCaixaAlta($estado)] ?? '';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $id . '/municipios');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $dado = jsonDecode(curl_exec($ch), true);
        if (!is_array($dado) || !array_key_exists(0, $dado) || !array_key_exists('id', $dado[0])) {
            $this->mensagemErroApi();
        }

        $cidade = [];
        foreach ($dado as $r) {
            $nome = $r['nome'];
            $cidade[$nome] = $nome;
        }

        return [
            'status' => 'sucesso',
            'dado' => $cidade
        ];
    }

    private function googleKey()
    {
        $apiKey = env('GOOGLE_API_KEY');
        if (empty($apiKey)) {
            $this->mensagemErroApi('Não foi passado uma API KEY do GOOGLE.');
        }
        return $apiKey;
    }

    private function mensagemErroApi(string $mensagem = '')
    {
        mensagemErro('Erro na API', 'Não foi possível conectar com a API.', localhost: $mensagem);
    }
}
