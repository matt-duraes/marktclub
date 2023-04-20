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
     * @param  array|stdClass  $lista
     * @param  string          $indice
     * @param  string          $valor
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
     * @return void
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
            'solteiro' => 'Solteiro',
            'casado' => 'Casado',
            'divorciado' => 'Divorciado',
            'viuvo' => 'Viúvo',
            'separado' => 'Separado'
        ]);
        return $this;
    }

    /**
     * Adiciona um valor a lista
     *
     * @param  mixed   $indice  Um índice para ser adicionado
     * @param  string  $titulo  Um título para ser adicionado
     * @param  array   $lista   Um array com uma lista de itens a ser adicionado
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
     * @return void
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
            'masculino' => 'Masculino',
            'feminino' => 'Feminino',
            'outro' => 'Outro',
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
}
