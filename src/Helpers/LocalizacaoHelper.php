<?php

namespace Helpers;

use Erro\Excecao;

final class LocalizacaoHelper
{
    /**
     * Busca os dados geográficos pelo IP do usuário
     *
     * @param  string|null $ip IP do usuário ou null para tentar pegar IP automático
     * @return array
     */
    public function pegarDadosPeloIp(string $ip = null): array
    {
        $ip = !empty($ip) ? $ip : ip();
        $url = (string)"http://ip-api.com/json/{$ip}?fields=65535";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $retorno = jsonDecode(curl_exec($ch), true);

        if (existeErro($retorno, 'status') || $retorno['status'] != 'success') {
            $this->mensagemErroApi();
        }

        return [
            'pais'      => $retorno['countryCode'] ?? '',
            'estado'    => $retorno['region'] ?? '',
            'cidade'    => $retorno['city'] ?? '',
            'latitude'  => $retorno['lat'] ?? '',
            'longitude' => $retorno['lon'] ?? '',
            'provedor'  => $retorno['isp'] ?? '',
        ];
    }

    /**
     * @param  string  $mensagem
     * @throws Excecao
     */
    private function mensagemErroApi(string $mensagem = ''): void
    {
        mensagemErro('Erro', 'Ocorreu um erro, por favor, tente novamente.', localhost: $mensagem);
    }

    /**
     * @param  string|int|null $cep
     * @return array
     * @throws Excecao
     */
    public function pegarEnderecoPeloCep(null|string|int $cep): array
    {
        $ch = curl_init('https://brasilapi.com.br/api/cep/v1/' . preg_replace('/[^0-9]/', '', $cep));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $retorno = jsonDecode(curl_exec($ch), true);
        if (!is_array($retorno) || !array_key_exists('cep', $retorno)) {
            $this->mensagemErroApi();
        }
        return [
            'logradouro' => $retorno['street'] ?? '',
            'bairro'     => $retorno['neighborhood'] ?? '',
            'cidade'     => $retorno['city'] ?? '',
            'estado'     => $retorno['state'] ?? '',
            'cep'        => array_key_exists('cep', $retorno) && !empty($retorno['cep']) ? soNumero($retorno['cep']) : '',
            'pais'       => array_key_exists('state', $retorno) && !empty($retorno['state']) ? 'BR' : '',
        ];
    }

    /**
     * @throws Excecao
     */
    public function pegarEnderecoPelaGeolocalizacao($latitude, $longitude): array
    {
        $parameters = $latitude . ',' . $longitude . '&key=' . $this->googleKey();
        $url = 'https://maps.google.com/maps/api/geocode/json?latlng=' . $parameters;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $retorno = jsonDecode(curl_exec($ch), true);

        if (empty($retorno['results'])) {
            $this->mensagemErroApi();
        }

        $endereco = [
            'bairro' => '',
            'cidade' => '',
            'estado' => '',
            'cep'    => '',
            'pais'   => '',
        ];

        foreach ($retorno['results'] as $r) {
            if ($r['geometry']['location']['lat'] == $latitude && $r['geometry']['location']['lng'] == $longitude) {
                foreach ($r['address_components'] as $campo) {
                    if (!array_key_exists('types', $campo) || !array_key_exists('short_name', $campo)) {
                        continue;
                    } elseif (in_array('administrative_area_level_4', $campo['types'])) {
                        $endereco['bairro'] = $campo['short_name'];
                    } elseif (in_array('administrative_area_level_2', $campo['types'])) {
                        $endereco['cidade'] = $campo['short_name'];
                    } elseif (in_array('administrative_area_level_1', $campo['types'])) {
                        $endereco['estado'] = $campo['short_name'];
                    } elseif (in_array('country', $campo['types'])) {
                        $endereco['pais'] = $campo['short_name'];
                    } elseif (in_array('postal_code', $campo['types'])) {
                        $endereco['cep'] = soNumero($campo['short_name']);
                    }
                }
                break;
            }
        }

        return $endereco;
    }

    /**
     * @throws Excecao
     */
    private function googleKey()
    {
        $apiKey = env('GOOGLE_API_KEY');
        if (empty($apiKey)) {
            $this->mensagemErroApi('Não foi passado uma API KEY do GOOGLE.');
        }
        return $apiKey;
    }

    /**
     * @param  string|null $pais
     * @param  string|null $titulo
     * @param  string|null $cep
     * @param  string|null $logradouro
     * @param  string|null $numero
     * @param  string|null $bairro
     * @param  string|null $cidade
     * @param  string|null $estado
     * @return array|void
     * @throws Excecao
     */
    public function pegarGeolocalizacaoPeloEndereco(
        string $pais = null,
        string $titulo = null,
        string $cep = null,
        string $logradouro = null,
        string $numero = null,
        string $bairro = null,
        string $cidade = null,
        string $estado = null,
    ) {
        mensagemErro('Erro!', 'Não foi possível achar uma geolocalização pelo endereço');
        $dado = $this->buscarGeolocalizacaoNoGoogle(
            pais: $pais,
            titulo: $titulo
        );
        if (is_array($dado)) {
            return $dado;
        }
        $dado = $this->buscarGeolocalizacaoNoGoogle(
            pais: $pais,
            cep: $cep,
            logradouro: $logradouro,
            numero: $numero,
            bairro: $bairro,
            cidade: $cidade,
            estado: $estado
        );
        if (is_array($dado)) {
            return $dado;
        }
        $dado = $this->buscarGeolocalizacaoNoGoogle(
            pais: $pais,
            logradouro: $logradouro,
            numero: $numero,
            bairro: $bairro,
            cidade: $cidade,
            estado: $estado
        );
        if (is_array($dado)) {
            return $dado;
        }
        $dado = $this->buscarGeolocalizacaoNoGoogle(
            pais: $pais,
            bairro: $bairro,
            cidade: $cidade,
            estado: $estado
        );
        if (is_array($dado)) {
            return $dado;
        }
        $dado = $this->buscarGeolocalizacaoNoGoogle(
            pais: $pais,
            cidade: $cidade,
            estado: $estado
        );
        if (is_array($dado)) {
            return $dado;
        }
        $dado = $this->buscarGeolocalizacaoNoGoogle(
            pais: $pais,
            estado: $estado
        );
        if (is_array($dado)) {
            return $dado;
        }
        mensagemErro('Erro!', 'Não foi possível achar uma geolocalização pelo endereço');
    }

    /**
     * @param  string|null $pais
     * @param  string|null $titulo
     * @param  string|null $cep
     * @param  string|null $logradouro
     * @param  string|null $numero
     * @param  string|null $bairro
     * @param  string|null $cidade
     * @param  string|null $estado
     * @return bool|array
     * @throws Excecao
     */
    private function buscarGeolocalizacaoNoGoogle(
        string $pais = null,
        string $titulo = null,
        string $cep = null,
        string $logradouro = null,
        string $numero = null,
        string $bairro = null,
        string $cidade = null,
        string $estado = null,
    ): bool|array {
        $pais = empty($pais) ? 'BR' : $pais;

        if (empty($titulo) && empty($logradouro) && empty($bairro) && empty($cidade) && empty($estado)) {
            return false;
        }

        $endereco = '';
        if (!empty($logradouro)) {
            $endereco .= $logradouro;
        }
        if (!empty($numero)) {
            $endereco .= !empty($endereco) ? ' ' . $numero : $numero;
        }
        if (!empty($bairro)) {
            $endereco .= !empty($endereco) ? ', ' . $bairro : $bairro;
        }
        if (!empty($cidade)) {
            $endereco .= !empty($endereco) ? ' - ' . $cidade : $cidade;
        }
        if (!empty($cidade) && !empty($estado)) {
            $endereco .= '/' . $estado;
        } elseif (!empty($estado)) {
            $endereco .= !empty($endereco) ? ' - ' . $estado : $estado;
        }
        if (!empty($cep)) {
            $endereco .= !empty($endereco) ? ' - CEP: ' . $cep : 'CEP: ' . $cidade;
        }

        if (!empty($titulo)) {
            $endereco = $titulo;
        }

        $url = 'https://maps.google.com/maps/api/geocode/json?address=' . urlencode(
            $endereco
        ) . '&key=' . $this->googleKey() . '&region=' . $pais . '&components=country:' . $pais . '|language:pt-BR';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $dado = jsonDecode(curl_exec($ch), true);
        if (!$this->validarRetornoGeolocalizacaoDoGoogle($dado)) {
            return false;
        }
        return [
            'latitude'  => $dado['results'][0]['geometry']['location']['lat'],
            'longitude' => $dado['results'][0]['geometry']['location']['lng'],
        ];
    }

    /**
     * @param       $retorno
     * @return bool
     */
    private function validarRetornoGeolocalizacaoDoGoogle($retorno): bool
    {
        return is_array($retorno) &&
            array_key_exists('results', $retorno) &&
            array_key_exists(0, $retorno['results']) &&
            array_key_exists('formatted_address', $retorno['results'][0]);
    }

    /**
     * Pega a lista de cidades pelo UF enviado
     *
     * @param  string  $estado UF do estado que deseja buscar as cidades
     * @param  string  $indice Indice para o primeiro elemento (opcional)
     * @param  string  $titulo Valor do primeiro elemento (opcional)
     * @return array
     * @throws Excecao
     */
    public function pegarListaCidadePeloEstado(
        string $estado,
        string $indice = '',
        string $titulo = ''
    ): array {
        $id = [
            'RO' => 11, 'AC' => 12, 'AM' => 13, 'RR' => 14, 'PA' => 15, 'AP' => 16,
            'TO' => 17, 'MA' => 21, 'PI' => 22, 'CE' => 23, 'RN' => 24, 'PB' => 25,
            'PE' => 26, 'AL' => 27, 'SE' => 28, 'BA' => 29, 'MG' => 31, 'ES' => 32,
            'RJ' => 33, 'SP' => 35, 'PR' => 41, 'SC' => 42, 'RS' => 43, 'MS' => 50,
            'MT' => 51, 'GO' => 52, 'DF' => 53
        ][strCaixaAlta($estado)] ?? '';

        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $id . '/municipios'
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $dado = jsonDecode(curl_exec($ch), true);
        if (!is_array($dado) || !array_key_exists(0, $dado) || !array_key_exists('id', $dado[0])) {
            $this->mensagemErroApi();
        }

        $cidade = [];
        if (!empty($titulo)) {
            $cidade[$indice] = $titulo;
        }
        foreach ($dado as $r) {
            $nome = $r['nome'];
            $cidade[$nome] = $nome;
        }

        return $cidade;
    }
}
