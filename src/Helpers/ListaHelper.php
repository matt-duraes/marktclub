<?php

namespace Helpers;

use stdClass;

final class ListaHelper
{
    private $lista;
    private $tipo;

    public function r()
    {
        $valor = $this->lista;
        $this->lista = '';
        return $valor;
    }

    /**
     * @param  array|stdClass $lista
     * @param  string         $indice
     * @param  string         $valor
     * @return $this
     */
    public function lista(array|stdClass $lista, string $indice, string $valor): ListaHelper
    {
        $lista = is_object($lista) ? (array)$lista : $lista;
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[$r->$indice] = $r->$valor;
        }

        $this->addLista($retorno);
        return $this;
    }

    /**
     * @param $lista
     */
    private function addLista($lista): void
    {
        $atual = $this->lista;
        if (is_array($atual) && $atual) {
            $this->lista = $atual + $lista;
        } else {
            $this->lista = $lista;
        }
    }

    public function estadoCivil(): ListaHelper
    {
        $this->add(lista: [
            'solteiro'   => 'Solteiro',
            'casado'     => 'Casado',
            'divorciado' => 'Divorciado',
            'viuvo'      => 'Viúvo',
            'separado'   => 'Separado'
        ]);
        return $this;
    }

    /**
     * Adiciona um valor a lista
     *
     * @param mixed  $indice Um índice para ser adicionado
     * @param string $titulo Um título para ser adicionado
     * @param array  $lista  Um array com uma lista de itens a ser adicionado
     */
    public function add(mixed $indice = 0, string $titulo = '', array $lista = []): ListaHelper
    {
        if (empty($indice) && empty($titulo) && $lista) {
            $this->addLista($lista);
            return $this;
        }
        $this->addItem($indice, $titulo);
        return $this;
    }

    /**
     * @param $indice
     * @param $titulo
     */
    private function addItem($indice, $titulo): void
    {
        $item = [$indice => $titulo];
        $atual = $this->lista;
        if (is_array($atual) && $atual) {
            $this->lista = $atual + $item;
        } else {
            $this->lista = $item;
        }
    }

    public function genero(): ListaHelper
    {
        $this->add(lista: [
            'masculino'    => 'Masculino',
            'feminino'     => 'Feminino',
            'outro'        => 'Outro',
            'nao-informar' => 'Não informar'
        ]);
        return $this;
    }

    public function estado(): ListaHelper
    {
        $this->add(lista: [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins'
        ]);

        return $this;
    }

    public function uf(): ListaHelper
    {
        $this->add(lista: [
            'AC' => 'AC',
            'AL' => 'AL',
            'AP' => 'AP',
            'AM' => 'AM',
            'BA' => 'BA',
            'CE' => 'CE',
            'DF' => 'DF',
            'ES' => 'ES',
            'GO' => 'GO',
            'MA' => 'MA',
            'MT' => 'MT',
            'MS' => 'MS',
            'MG' => 'MG',
            'PA' => 'PA',
            'PB' => 'PB',
            'PR' => 'PR',
            'PE' => 'PE',
            'PI' => 'PI',
            'RJ' => 'RJ',
            'RN' => 'RN',
            'RS' => 'RS',
            'RO' => 'RO',
            'RR' => 'RR',
            'SC' => 'SC',
            'SP' => 'SP',
            'SE' => 'SE',
            'TO' => 'TO'
        ]);
        return $this;
    }

    public function pais(): ListaHelper
    {
        $this->add(lista: [
            'AF' => 'Afeganistão',
            'ZA' => 'África do Sul',
            'AL' => 'Albânia',
            'DE' => 'Alemanha',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antártida',
            'AG' => 'Antígua e Barbuda',
            'AN' => 'Antilhas Holandesas',
            'SA' => 'Arábia Saudita',
            'DZ' => 'Argélia',
            'AR' => 'Argentina',
            'AM' => 'Armênia',
            'AW' => 'Aruba',
            'AU' => 'Austrália',
            'AT' => 'Áustria',
            'AZ' => 'Azerbaijão',
            'BS' => 'Bahamas',
            'BH' => 'Bahrein',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Bélgica',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermudas',
            'BO' => 'Bolívia',
            'BA' => 'Bósnia-Herzegóvina',
            'BW' => 'Botsuana',
            'BR' => 'Brasil',
            'BN' => 'Brunei',
            'BG' => 'Bulgária',
            'BF' => 'Burkina Fasso',
            'BI' => 'Burundi',
            'BT' => 'Butão',
            'CV' => 'Cabo Verde',
            'CM' => 'Camarões',
            'KH' => 'Camboja',
            'CA' => 'Canadá',
            'KZ' => 'Cazaquistão',
            'TD' => 'Chade',
            'CL' => 'Chile',
            'CN' => 'China',
            'CY' => 'Chipre',
            'SG' => 'Cingapura',
            'CO' => 'Colômbia',
            'CG' => 'Congo',
            'KP' => 'Coréia do Norte',
            'KR' => 'Coréia do Sul',
            'CI' => 'Costa do Marfim',
            'CR' => 'Costa Rica',
            'HR' => 'Croácia',
            'CU' => 'Cuba',
            'DK' => 'Dinamarca',
            'DJ' => 'Djibuti',
            'DM' => 'Dominica',
            'EG' => 'Egito',
            'SV' => 'El Salvador',
            'AE' => 'Emirados Árabes Unidos',
            'EC' => 'Equador',
            'ER' => 'Eritréia',
            'SK' => 'Eslováquia',
            'SI' => 'Eslovênia',
            'ES' => 'Espanha',
            'US' => 'Estados Unidos',
            'EE' => 'Estônia',
            'ET' => 'Etiópia',
            'FJ' => 'Fiji',
            'PH' => 'Filipinas',
            'FI' => 'Finlândia',
            'FR' => 'França',
            'GA' => 'Gabão',
            'GM' => 'Gâmbia',
            'GH' => 'Gana',
            'GE' => 'Geórgia',
            'GI' => 'Gibraltar',
            'GB' => 'Grã-Bretanha',
            'GD' => 'Granada',
            'GR' => 'Grécia',
            'GL' => 'Groelândia',
            'GP' => 'Guadalupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GY' => 'Guiana',
            'GF' => 'Guiana Francesa',
            'GN' => 'Guiné',
            'GQ' => 'Guiné Equatorial',
            'GW' => 'Guiné-Bissau',
            'HT' => 'Haiti',
            'NL' => 'Holanda',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungria',
            'YE' => 'Iêmen',
            'BV' => 'Ilha Bouvet',
            'IM' => 'Ilha do Homem',
            'CX' => 'Ilha Natal',
            'PN' => 'Ilha Pitcairn',
            'RE' => 'Ilha Reunião',
            'AX' => 'Ilhas Aland',
            'KY' => 'Ilhas Cayman',
            'CC' => 'Ilhas Cocos',
            'KM' => 'Ilhas Comores',
            'CK' => 'Ilhas Cook',
            'FO' => 'Ilhas Faroes',
            'FK' => 'Ilhas Falkland',
            'GS' => 'Ilhas Geórgia do Sul e Sandwich do Sul',
            'HM' => 'Ilhas Heard e McDonald',
            'MP' => 'Ilhas Marianas do Norte',
            'MH' => 'Ilhas Marshall',
            'UM' => 'Ilhas Menores dos Estados Unidos',
            'NF' => 'Ilhas Norfolk',
            'SC' => 'Ilhas Seychelles',
            'SB' => 'Ilhas Solomão',
            'SJ' => 'Ilhas Svalbard e Jan Mayen',
            'TK' => 'Ilhas Tokelau',
            'TC' => 'Ilhas Turks e Caicos',
            'VI' => 'Ilhas Virgens',
            'VG' => 'Ilhas Virgens',
            'WF' => 'Ilhas Wallis e Futuna',
            'IN' => 'índia',
            'ID' => 'Indonésia',
            'IR' => 'Irã',
            'IQ' => 'Iraque',
            'IE' => 'Irlanda',
            'IS' => 'Islândia',
            'IL' => 'Israel',
            'IT' => 'Itália',
            'JM' => 'Jamaica',
            'JP' => 'Japão',
            'JE' => 'Jersey',
            'JO' => 'Jordânia',
            'KE' => 'Kênia',
            'KI' => 'Kiribati',
            'KW' => 'Kuait',
            'LA' => 'Laos',
            'LV' => 'Látvia',
            'LS' => 'Lesoto',
            'LB' => 'Líbano',
            'LR' => 'Libéria',
            'LY' => 'Líbia',
            'LI' => 'Liechtenstein',
            'LT' => 'Lituânia',
            'LU' => 'Luxemburgo',
            'MO' => 'Macau',
            'MK' => 'Macedônia',
            'MG' => 'Madagascar',
            'MY' => 'Malásia',
            'MW' => 'Malaui',
            'MV' => 'Maldivas',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MA' => 'Marrocos',
            'MQ' => 'Martinica',
            'MU' => 'Maurício',
            'MR' => 'Mauritânia',
            'YT' => 'Mayotte',
            'MX' => 'México',
            'FM' => 'Micronésia',
            'MZ' => 'Moçambique',
            'MD' => 'Moldova',
            'MC' => 'Mônaco',
            'MN' => 'Mongólia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MM' => 'Myanma',
            'NA' => 'Namíbia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NI' => 'Nicarágua',
            'NE' => 'Níger',
            'NG' => 'Nigéria',
            'NU' => 'Niue',
            'NO' => 'Noruega',
            'NC' => 'Nova Caledônia',
            'NZ' => 'Nova Zelândia',
            'OM' => 'Omã',
            'PW' => 'Palau',
            'PA' => 'Panamá',
            'PG' => 'Papua-Nova Guiné',
            'PK' => 'Paquistão',
            'PY' => 'Paraguai',
            'PE' => 'Peru',
            'PF' => 'Polinésia Francesa',
            'PL' => 'Polônia',
            'PR' => 'Porto Rico',
            'PT' => 'Portugal',
            'QA' => 'Qatar',
            'KG' => 'Quirguistão',
            'CF' => 'República Centro-Africana',
            'CD' => 'República Democrática do Congo',
            'DO' => 'República Dominicana',
            'CZ' => 'República Tcheca',
            'RO' => 'Romênia',
            'RW' => 'Ruanda',
            'RU' => 'Rússia',
            'EH' => 'Saara Ocidental',
            'VC' => 'Saint Vincente e Granadinas',
            'AS' => 'Samoa Americana',
            'WS' => 'Samoa Ocidental',
            'SM' => 'San Marino',
            'SH' => 'Santa Helena',
            'LC' => 'Santa Lúcia',
            'BL' => 'São Bartolomeu',
            'KN' => 'São Cristóvão e Névis',
            'MF' => 'São Martim',
            'ST' => 'São Tomé e Príncipe',
            'SN' => 'Senegal',
            'SL' => 'Serra Leoa',
            'RS' => 'Sérvia',
            'SY' => 'Síria',
            'SO' => 'Somália',
            'LK' => 'Sri Lanka',
            'PM' => 'St. Pierre and Miquelon',
            'SZ' => 'Suazilândia',
            'SD' => 'Sudão',
            'SE' => 'Suécia',
            'CH' => 'Suíça',
            'SR' => 'Suriname',
            'TJ' => 'Tadjiquistão',
            'TH' => 'Tailândia',
            'TW' => 'Taiwan',
            'TZ' => 'Tanzânia',
            'IO' => 'Território Britânico do Oceano índico',
            'TF' => 'Territórios do Sul da França',
            'PS' => 'Territórios Palestinos Ocupados',
            'TP' => 'Timor Leste',
            'TG' => 'Togo',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunísia',
            'TM' => 'Turcomenistão',
            'TR' => 'Turquia',
            'TV' => 'Tuvalu',
            'UA' => 'Ucrânia',
            'UG' => 'Uganda',
            'UY' => 'Uruguai',
            'UZ' => 'Uzbequistão',
            'VU' => 'Vanuatu',
            'VA' => 'Vaticano',
            'VE' => 'Venezuela',
            'VN' => 'Vietnã',
            'ZM' => 'Zâmbia',
            'ZW' => 'Zimbábue'
        ]);
        return $this;
    }

    public function status(): ListaHelper
    {
        $this->add(lista: [1 => 'Ativo', 2 => 'Inativo']);
        return $this;
    }

    public function mes(): ListaHelper
    {
        $this->add(lista: [
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ]);
        return $this;
    }

    public function semana(): ListaHelper
    {
        $this->add(lista: [
            'Domingo',
            'Segunda-Feira',
            'Terça-Feira',
            'Quarta-Feira',
            'Quinta-Feira',
            'Sexta-Feira',
            'Sábado'
        ]);
        return $this;
    }

    public function ddi(): ListaHelper
    {
        $pais = [
            ['codigo'=>93, 'nome'=>'Afeganistão'],
            ['codigo'=>27, 'nome'=>'Africa do Sul'],
            ['codigo'=>355, 'nome'=>'Albânia'],
            ['codigo'=>49, 'nome'=>'Alemanha'],
            ['codigo'=>376, 'nome'=>'Andorra'],
            ['codigo'=>244, 'nome'=>'Angola'],
            ['codigo'=>1, 'nome'=>'Anguilla'],
            ['codigo'=>1, 'nome'=>'Antígua e Barbuda'],
            ['codigo'=>599, 'nome'=>'Antilhas Holandesas'],
            ['codigo'=>966, 'nome'=>'Arábia Saudita'],
            ['codigo'=>213, 'nome'=>'Argélia'],
            ['codigo'=>54, 'nome'=>'Argentina'],
            ['codigo'=>374, 'nome'=>'Armênia'],
            ['codigo'=>297, 'nome'=>'Aruba'],
            ['codigo'=>247, 'nome'=>'Ascensão'],
            ['codigo'=>61, 'nome'=>'Austrália'],
            ['codigo'=>43, 'nome'=>'Áustria'],
            ['codigo'=>994, 'nome'=>'Azerbaijão'],
            ['codigo'=>1, 'nome'=>'Bahamas'],
            ['codigo'=>880, 'nome'=>'Bangladesh'],
            ['codigo'=>1, 'nome'=>'Barbados'],
            ['codigo'=>973, 'nome'=>'Bahrein'],
            ['codigo'=>32, 'nome'=>'Bélgica'],
            ['codigo'=>501, 'nome'=>'Belize'],
            ['codigo'=>229, 'nome'=>'Benim'],
            ['codigo'=>1, 'nome'=>'Bermudas'],
            ['codigo'=>375, 'nome'=>'Bielorrússia'],
            ['codigo'=>591, 'nome'=>'Bolívia'],
            ['codigo'=>387, 'nome'=>'Bósnia e Herzegovina'],
            ['codigo'=>267, 'nome'=>'Botswana'],
            ['codigo'=>55, 'nome'=>'Brasil'],
            ['codigo'=>673, 'nome'=>'Brunei'],
            ['codigo'=>359, 'nome'=>'Bulgária'],
            ['codigo'=>226, 'nome'=>'Burkina Faso'],
            ['codigo'=>257, 'nome'=>'Burundi'],
            ['codigo'=>975, 'nome'=>'Butão'],
            ['codigo'=>238, 'nome'=>'Cabo Verde'],
            ['codigo'=>237, 'nome'=>'Camarões'],
            ['codigo'=>855, 'nome'=>'Camboja'],
            ['codigo'=>1, 'nome'=>'Canadá'],
            ['codigo'=>7, 'nome'=>'Cazaquistão'],
            ['codigo'=>237, 'nome'=>'Chade'],
            ['codigo'=>56, 'nome'=>'Chile'],
            ['codigo'=>86, 'nome'=>'China'],
            ['codigo'=>357, 'nome'=>'Chipre'],
            ['codigo'=>57, 'nome'=>'Colômbia'],
            ['codigo'=>269, 'nome'=>'Comores'],
            ['codigo'=>242, 'nome'=>'Congo-Brazzaville<'],
            ['codigo'=>243, 'nome'=>'Congo-Kinshasa'],
            ['codigo'=>850, 'nome'=>'Coreia do Norte'],
            ['codigo'=>82, 'nome'=>'Coreia do Sul'],
            ['codigo'=>225, 'nome'=>'Costa do Marfim'],
            ['codigo'=>506, 'nome'=>'Costa Rica'],
            ['codigo'=>385, 'nome'=>'Croácia'],
            ['codigo'=>53, 'nome'=>'Cuba'],
            ['codigo'=>45, 'nome'=>'Dinamarca'],
            ['codigo'=>253, 'nome'=>'Djibuti'],
            ['codigo'=>1, 'nome'=>'Dominica'],
            ['codigo'=>20, 'nome'=>'Egipto'],
            ['codigo'=>503, 'nome'=>'El Salvador'],
            ['codigo'=>971, 'nome'=>'Emirados Árabes Unidos'],
            ['codigo'=>593, 'nome'=>'Equador'],
            ['codigo'=>291, 'nome'=>'Eritreia'],
            ['codigo'=>421, 'nome'=>'Eslováquia'],
            ['codigo'=>386, 'nome'=>'Eslovénia'],
            ['codigo'=>34, 'nome'=>'Espanha'],
            ['codigo'=>1, 'nome'=>'Estados Unidos'],
            ['codigo'=>372, 'nome'=>'Estónia'],
            ['codigo'=>251, 'nome'=>'Etiópia'],
            ['codigo'=>679, 'nome'=>'Fiji'],
            ['codigo'=>63, 'nome'=>'Filipinas'],
            ['codigo'=>358, 'nome'=>'Finlândia'],
            ['codigo'=>33, 'nome'=>'França'],
            ['codigo'=>241, 'nome'=>'Gabão'],
            ['codigo'=>220, 'nome'=>'Gâmbia'],
            ['codigo'=>233, 'nome'=>'Gana'],
            ['codigo'=>995, 'nome'=>'Geórgia'],
            ['codigo'=>350, 'nome'=>'Gibraltar'],
            ['codigo'=>1, 'nome'=>'Granada'],
            ['codigo'=>30, 'nome'=>'Grécia'],
            ['codigo'=>299, 'nome'=>'Groenlândia'],
            ['codigo'=>590, 'nome'=>'Guadalupe'],
            ['codigo'=>671, 'nome'=>'Guam'],
            ['codigo'=>502, 'nome'=>'Guatemala'],
            ['codigo'=>592, 'nome'=>'Guiana'],
            ['codigo'=>594, 'nome'=>'Guiana Francesa'],
            ['codigo'=>224, 'nome'=>'Guiné'],
            ['codigo'=>245, 'nome'=>'Guiné-Bissau'],
            ['codigo'=>240, 'nome'=>'Guiné Equatorial'],
            ['codigo'=>509, 'nome'=>'Haiti'],
            ['codigo'=>504, 'nome'=>'Honduras'],
            ['codigo'=>852, 'nome'=>'Hong Kong'],
            ['codigo'=>36, 'nome'=>'Hungria'],
            ['codigo'=>967, 'nome'=>'Iêmen'],
            ['codigo'=>1, 'nome'=>'Ilhas Cayman'],
            ['codigo'=>672, 'nome'=>'Ilha Christmas'],
            ['codigo'=>672, 'nome'=>'Ilhas Cocos'],
            ['codigo'=>682, 'nome'=>'Ilhas Cook'],
            ['codigo'=>298, 'nome'=>'Ilhas Féroe'],
            ['codigo'=>672, 'nome'=>'Ilha Heard e Ilhas McDonald'],
            ['codigo'=>960, 'nome'=>'Maldivas'],
            ['codigo'=>500, 'nome'=>'Ilhas Malvinas'],
            ['codigo'=>1, 'nome'=>'Ilhas Marianas do Norte'],
            ['codigo'=>692, 'nome'=>'Ilhas Marshall'],
            ['codigo'=>672, 'nome'=>'Ilha Norfolk'],
            ['codigo'=>677, 'nome'=>'Ilhas Salomão'],
            ['codigo'=>1, 'nome'=>'Ilhas Virgens Americanas'],
            ['codigo'=>1, 'nome'=>'Ilhas Virgens Britânicas'],
            ['codigo'=>91, 'nome'=>'Índia'],
            ['codigo'=>62, 'nome'=>'Indonésia'],
            ['codigo'=>98, 'nome'=>'Irã'],
            ['codigo'=>964, 'nome'=>'Iraque'],
            ['codigo'=>353, 'nome'=>'Irlanda'],
            ['codigo'=>354, 'nome'=>'Islândia'],
            ['codigo'=>972, 'nome'=>'Israel'],
            ['codigo'=>39, 'nome'=>'Itália'],
            ['codigo'=>1, 'nome'=>'Jamaica'],
            ['codigo'=>81, 'nome'=>'Japão'],
            ['codigo'=>962, 'nome'=>'Jordânia'],
            ['codigo'=>686, 'nome'=>'Kiribati'],
            ['codigo'=>383, 'nome'=>'Kosovo'],
            ['codigo'=>965, 'nome'=>'Kuwait'],
            ['codigo'=>856, 'nome'=>'Laos'],
            ['codigo'=>266, 'nome'=>'Lesoto'],
            ['codigo'=>371, 'nome'=>'Letônia'],
            ['codigo'=>961, 'nome'=>'Líbano'],
            ['codigo'=>231, 'nome'=>'Libéria'],
            ['codigo'=>218, 'nome'=>'Líbia'],
            ['codigo'=>423, 'nome'=>'Liechtenstein'],
            ['codigo'=>370, 'nome'=>'Lituânia'],
            ['codigo'=>352, 'nome'=>'Luxemburgo'],
            ['codigo'=>853, 'nome'=>'Macau'],
            ['codigo'=>389, 'nome'=>'República da Macedônia'],
            ['codigo'=>261, 'nome'=>'Madagascar'],
            ['codigo'=>60, 'nome'=>'Mal'],
            ['codigo'=>265, 'nome'=>'Malawi'],
            ['codigo'=>223, 'nome'=>'Mali'],
            ['codigo'=>356, 'nome'=>'Malta'],
            ['codigo'=>212, 'nome'=>'Marrocos'],
            ['codigo'=>596, 'nome'=>'Martinica'],
            ['codigo'=>230, 'nome'=>'Maurícia'],
            ['codigo'=>222, 'nome'=>'Mauritânia'],
            ['codigo'=>269, 'nome'=>'Mayotte'],
            ['codigo'=>52, 'nome'=>'México'],
            ['codigo'=>691, 'nome'=>'Estados Federados da Micronésia'],
            ['codigo'=>258, 'nome'=>'Moçambique'],
            ['codigo'=>373, 'nome'=>'Moldávia'],
            ['codigo'=>377, 'nome'=>'Mônaco'],
            ['codigo'=>976, 'nome'=>'Mongólia'],
            ['codigo'=>382, 'nome'=>'Montenegro'],
            ['codigo'=>1, 'nome'=>'Montserrat'],
            ['codigo'=>95, 'nome'=>'Myanmar'],
            ['codigo'=>264, 'nome'=>'Namíbia'],
            ['codigo'=>674, 'nome'=>'Nauru'],
            ['codigo'=>977, 'nome'=>'Nepal'],
            ['codigo'=>505, 'nome'=>'Nicarágua'],
            ['codigo'=>227, 'nome'=>'Níger'],
            ['codigo'=>234, 'nome'=>'Nigéria'],
            ['codigo'=>683, 'nome'=>'Niue'],
            ['codigo'=>47, 'nome'=>'Noruega'],
            ['codigo'=>687, 'nome'=>'Nova Caledônia'],
            ['codigo'=>64, 'nome'=>'Nova Zelândia'],
            ['codigo'=>968, 'nome'=>'Omã'],
            ['codigo'=>31, 'nome'=>'Países Baixos'],
            ['codigo'=>680, 'nome'=>'Palau'],
            ['codigo'=>970, 'nome'=>'Palestina'],
            ['codigo'=>507, 'nome'=>'Panamá'],
            ['codigo'=>675, 'nome'=>'Papua-Nova Guiné'],
            ['codigo'=>92, 'nome'=>'Paquistão'],
            ['codigo'=>595, 'nome'=>'Paraguai'],
            ['codigo'=>51, 'nome'=>'Peru'],
            ['codigo'=>689, 'nome'=>'Polinésia Francesa'],
            ['codigo'=>48, 'nome'=>'Polônia'],
            ['codigo'=>1, 'nome'=>'Porto Rico'],
            ['codigo'=>351, 'nome'=>'Portugal'],
            ['codigo'=>974, 'nome'=>'Qatar'],
            ['codigo'=>254, 'nome'=>'Quênia'],
            ['codigo'=>996, 'nome'=>'Quirguistão'],
            ['codigo'=>44, 'nome'=>'Reino Unido'],
            ['codigo'=>236, 'nome'=>'República Centro-Africana'],
            ['codigo'=>1, 'nome'=>'República Dominicana'],
            ['codigo'=>420, 'nome'=>'República Tcheca'],
            ['codigo'=>262, 'nome'=>'Reunião'],
            ['codigo'=>40, 'nome'=>'Romênia'],
            ['codigo'=>250, 'nome'=>'Ruanda'],
            ['codigo'=>7, 'nome'=>'Rússia'],
            ['codigo'=>212, 'nome'=>'Saara Ocidental'],
            ['codigo'=>685, 'nome'=>'Samoa'],
            ['codigo'=>1, 'nome'=>'Samoa Americana'],
            ['codigo'=>290, 'nome'=>'Santa Helena'],
            ['codigo'=>1, 'nome'=>'Santa Lúcia'],
            ['codigo'=>1, 'nome'=>'São Cristóvão e Nevis'],
            ['codigo'=>378, 'nome'=>'São Marinho'],
            ['codigo'=>508, 'nome'=>'Saint-Pierre e Miquelon'],
            ['codigo'=>239, 'nome'=>'São Tomé e Príncipe'],
            ['codigo'=>1, 'nome'=>'São Vicente e Granadinas'],
            ['codigo'=>248, 'nome'=>'Seicheles'],
            ['codigo'=>221, 'nome'=>'Senegal'],
            ['codigo'=>232, 'nome'=>'Serra Leoa'],
            ['codigo'=>381, 'nome'=>'Sérvia'],
            ['codigo'=>65, 'nome'=>'Singapura'],
            ['codigo'=>963, 'nome'=>'Síria'],
            ['codigo'=>252, 'nome'=>'Somália'],
            ['codigo'=>94, 'nome'=>'Sri Lanka'],
            ['codigo'=>268, 'nome'=>'Suazilândia'],
            ['codigo'=>249, 'nome'=>'Sudão'],
            ['codigo'=>211, 'nome'=>'Sudão do Sul'],
            ['codigo'=>46, 'nome'=>'Suécia'],
            ['codigo'=>41, 'nome'=>'Suíça'],
            ['codigo'=>597, 'nome'=>'Suriname'],
            ['codigo'=>992, 'nome'=>'Tadjiquistão'],
            ['codigo'=>66, 'nome'=>'Tailândia'],
            ['codigo'=>886, 'nome'=>'República da China'],
            ['codigo'=>255, 'nome'=>'Tanzânia'],
            ['codigo'=>246, 'nome'=>'Território Britânico do Oceano Índico'],
            ['codigo'=>670, 'nome'=>'Timor-Leste'],
            ['codigo'=>228, 'nome'=>'Togo'],
            ['codigo'=>690, 'nome'=>'Tokelau'],
            ['codigo'=>676, 'nome'=>'Tonga'],
            ['codigo'=>1, 'nome'=>'Trinidad e Tobago'],
            ['codigo'=>216, 'nome'=>'Tunísia'],
            ['codigo'=>1, 'nome'=>'Turcas e Caicos'],
            ['codigo'=>993, 'nome'=>'Turquemenistão'],
            ['codigo'=>90, 'nome'=>'Turquia'],
            ['codigo'=>688, 'nome'=>'Tuvalu'],
            ['codigo'=>380, 'nome'=>'Ucrânia'],
            ['codigo'=>256, 'nome'=>'Uganda'],
            ['codigo'=>598, 'nome'=>'Uruguai'],
            ['codigo'=>998, 'nome'=>'Uzbequistão'],
            ['codigo'=>678, 'nome'=>'Vanuatu'],
            ['codigo'=>379, 'nome'=>'Vaticano'],
            ['codigo'=>58, 'nome'=>'Venezuela'],
            ['codigo'=>84, 'nome'=>'Vietnã'],
            ['codigo'=>681, 'nome'=>'Wallis e Futuna'],
            ['codigo'=>260, 'nome'=>'Zâmbia'],
            ['codigo'=>263, 'nome'=>'Zimbábue']
        ];

        $array = [];
        if($pais):
            foreach($pais as $r):
                $array[$r['codigo']] = $r['codigo'];
            endforeach;
        endif;
        $arrayUnique = array_unique($array);
        natcasesort($arrayUnique);

        $this->add(lista: $arrayUnique);

        return $this;
    }
}
