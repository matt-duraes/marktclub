<?php

if (!function_exists('dataAdicionar')) {
    // doc
    // exemplo
    // echo dataAdicionar 2022-01-01,1,dia
    // echo dataAdicionar 2022-01-01,2,meses,d/m/Y
    /**
     * Adiciona um valor para a data enviada
     *
     * @param   string  $data       Data a ser convertida
     * @param   int     $numero     Número a ser adicionado
     * @param   string  $tempo      Tipo de tempo a ser adicionado, por exemplo, segundos, mimutos, horas, etc
     * @param   string  $formato    Formato de retorno da data
     * @return  string              Data com o formato passado em $formato, Y-m-d como padrão
     */
    function dataAdicionar(string $data, int $numero, string $tempo, string $formato = 'Y-m-d'): string
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->adicionar($numero, $tempo)->formato($formato);
    }
}
if (!function_exists('dataRemover')) {
    // doc
    // exemplo
    // echo dataRemover 2022-01-01,1,dia
    // echo dataRemover 2022-01-01,2,meses,d/m/Y
    /**
     * Remove um valor para a data enviada
     *
     * @param   string  $data       Data a ser convertida
     * @param   int     $numero     Número a ser removido
     * @param   string  $tempo      Tipo de tempo a ser removido, por exemplo, segundos, mimutos, horas, etc
     * @param   string  $formato    Formato de retorno da data
     * @return  string              Data com o formato passado em $formato, Y-m-d como padrão
     */
    function dataRemover(string $data, int $numero, string $tempo, string $formato = 'Y-m-d'): string
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->remover($numero, $tempo)->formato($formato);
    }
}

if (!function_exists('dataBr')) {
    // doc
    // exemplo
    // echo dataBr 2022-01-01
    /**
     * Converte a data enviada para padrão d/m/Y
     *
     * @param   null|string $data   Data a ser convertida
     * @return  string              Data no formato d/m/Y
     */
    function dataBr(?string $data): string
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->formato('d/m/Y');
    }
}
if (!function_exists('dataHoraBr')) {
    // doc
    // exemplo
    // echo dataHoraBr 2022-01-01_12:12:12
    /**
     * Converte a data enviada para padrão d/m/Y H:i:s
     *
     * @param null|string $data Data a ser convertida
     * @return  string              Data no formato d/m/Y H:i:s
     */
    function dataHoraBr(?string $data): string
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->formato('d/m/Y H:i:s');
    }
}
if (!function_exists('dataBanco')) {
    // doc
    // exemplo
    // echo dataBanco 01/01/2022
    /**
     * Converte a data enviada para padrão Y-m-d
     *
     * @param   null|string $data   Data a ser convertida
     * @return  string              Data no formato Y-m-d
     */
    function dataBanco(?string $data): string
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->formato('Y-m-d');
    }
}
if (!function_exists('dataHoraBanco')) {
    // doc
    // exemplo
    // echo dataHoraBanco 01/01/2022_12:12:12
    /**
     * Converte a data enviada para padrão Y-m-d H:i:s
     *
     * @param   null|string $data   Data a ser convertida
     * @return  string              Data no formato Y-m-d H:i:s
     */
    function dataHoraBanco(?string $data): string
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->formato('Y-m-d H:i:s');
    }
}

if (!function_exists('dataMesAno')) {
    // doc
    // exemplo
    // echo dataMesAno 01/10/2022
    // echo dataMesAno 2022-10-01
    /**
     * Converte a data enviada para padrão m/Y
     *
     * @param   null|string $data   Data a ser convertida
     * @return  string              Data no formato m/Y
     */
    function dataMesAno(string $data): string
    {
        return (new \Helpers\DataHelper)->valor($data)->formato('m/Y');
    }
}
if (!function_exists('dataAnoMes')) {
    // doc
    // exemplo
    // echo dataAnoMes 01/10/2022
    // echo dataAnoMes 2022-10-01
    /**
     * Converte a data enviada para padrão Y-m
     *
     * @param   null|string $data   Data a ser convertida
     * @return  string              Data no formato Y-m
     */
    function dataAnoMes(string $data): string
    {
        return (new \Helpers\DataHelper)->valor($data)->formato('Y-m');
    }
}

if (!function_exists('dataNomeMes')) {
    // doc
    // exemplo
    // echo dataNomeMes 01/10/2022
    // echo dataNomeMes 2022-08-01
    /**
     * Retorna o nome do mês da data enviada
     *
     * @param   string  $data   Data a ser convertida
     * @return  string          Nome do mês
     */
    function dataNomeMes(string $data)
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->nomeMes();
    }
}

if (!function_exists('dataNomeSemana')) {
    // doc
    // exemplo
    // echo dataNomeSemana 01/10/2022
    // echo dataNomeSemana 2022-10-02
    /**
     * Retorna o nome da semana da data enviada
     *
     * @param   string  $data   Data a ser convertida
     * @return  string          Nome da semana
     */
    function dataNomeSemana(string $data): string
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->nomeSemana();
    }
}

if (!function_exists('dataExtenso')) {
    // doc
    // exemplo
    // echo dataExtenso 01/10/2022
    // echo dataExtenso 2022-10-02
    /**
     * Converte a data para versão em entenso
     *
     * @param   string $data    Data a ser convertida
     * @return  string          Data por extenso
     */
    function dataExtenso(string $data)
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->extenso();
    }
}

if (!function_exists('dataDiferencaDia')) {
    // doc
    // exemplo
    // echo dataDiferencaDia 01/10/2022,02/10/2022
    // echo dataDiferencaDia 2022-10-02,2022-11-02
    /**
     * Retorna a diferença de dias entre duas datas
     *
     * @param   string      $dataInicial Primeira data a ser comparada
     * @param   string      $dataFinal   Segunda data a ser comparada
     * @return  int|bool                 Número de dias ou false em caso de erro
     */
    function dataDiferencaDia(string $dataInicial, string $dataFinal): int|bool
    {
        if (is_null($dataInicial) || is_null($dataFinal)) {
            return false;
        }
        return (new \Helpers\DataHelper)->valor($dataInicial)->diferencaDia($dataFinal);
    }
}
if (!function_exists('dataDiferencaHora')) {
    // doc
    // exemplo
    // echo dataDiferencaHora 01/10/2022,02/10/2022
    // echo dataDiferencaHora 2022-10-02,2022-11-02
    /**
     * Retorna a diferença de horas entre duas datas
     *
     * @param   string      $dataInicial Primeira data a ser comparada
     * @param   string      $dataFinal   Segunda data a ser comparada
     * @return  int|bool                 Número de dias ou false em caso de erro
     */
    function dataDiferencaHora(string $dataInicial, string $dataFinal): int|bool
    {
        if (is_null($dataInicial) || is_null($dataFinal)) {
            return false;
        }
        return (new \Helpers\DataHelper)->valor($dataInicial)->diferencaHora($dataFinal);
    }
}
if (!function_exists('dataDiferencaMinuto')) {
    // doc
    // exemplo
    // echo dataDiferencaMinuto 01/10/2022,02/10/2022
    // echo dataDiferencaMinuto 2022-10-02,2022-11-02
    /**
     * Retorna a diferença de minutos entre duas datas
     *
     * @param   string      $dataInicial Primeira data a ser comparada
     * @param   string      $dataFinal   Segunda data a ser comparada
     * @return  int|bool                 Número de dias ou false em caso de erro
     */
    function dataDiferencaMinuto(string $dataInicial, string $dataFinal): int|bool
    {
        if (is_null($dataInicial) || is_null($dataFinal)) {
            return false;
        }
        return (new \Helpers\DataHelper)->valor($dataInicial)->diferencaMinuto($dataFinal);
    }
}
if (!function_exists('dataDiferencaSegundo')) {
    // doc
    // exemplo
    // echo dataDiferencaSegundo 01/10/2022,02/10/2022
    // echo dataDiferencaSegundo 2022-10-02,2022-11-02
    /**
     * Retorna a diferença de dias entre duas datas
     *
     * @param   string      $dataInicial Primeira data a ser comparada
     * @param   string      $dataFinal   Segunda data a ser comparada
     * @return  int|bool                 Número de dias ou false em caso de erro
     */
    function dataDiferencaSegundo(string $dataInicial, string $dataFinal): int|bool
    {
        if (is_null($dataInicial) || is_null($dataFinal)) {
            return false;
        }
        return (new \Helpers\DataHelper)->valor($dataInicial)->diferencaSegundo($dataFinal);
    }
}

if (!function_exists('dataSocial')) {
    // doc
    // exemplo
    // echo dataSocial 01/10/2021
    // echo dataSocial 2021-10-01,true
    /**
     * Converte a data enviada para o padrão de rede social
     *
     * @param   null|string     $data   Data a ser convertida
     * @param   bool            $curto  True para data com padrão curto ou false para padrão normal
     * @return  string                  Data com o formato social
     */
    function dataSocial(?string $data, bool $curto = false): string
    {
        if (is_null($data)) {
            return '';
        }
        return (new \Helpers\DataHelper)->valor($data)->social($curto);
    }
}

if (!function_exists('dataIdade')) {
    // doc
    // exemplo
    // echo dataIdade 01/10/2020
    // echo dataIdade 2019-10-01
    /**
     * Pega a idade pela data enviada
     *
     * @param   string      $data   Data a ser convertida
     * @return  int|bool            Retorna a idade em inteiro ou false caso a data enviada seja inválida
     */
    function dataIdade(string $data): int|bool
    {
        if (is_null($data)) {
            return false;
        }
        return (new \Helpers\DataHelper)->valor($data)->idade();
    }
}

if (!function_exists('dataUltimoDiaMes')) {
    // doc
    // exemplo
    // echo dataUltimoDiaMes 01/10/2020
    // echo dataUltimoDiaMes 2019-10-01
    /**
     * Pega o último dia do mês
     *
     * @param   string      $data       Data a ser usada
     * @param   string      $formato    Formato de retorno da data
     * @return  string|bool             Retorna o último dia do mês
     */
    function dataUltimoDiaMes(string $data, string $formato = 'Y-m-d'): string|bool
    {
        if (empty($data)) {
            return false;
        }
        return (new \Helpers\DataHelper)->valor($data)->ultimoDiaMes()->r($formato);
    }
}
if (!function_exists('dataPrimeiroDiaMes')) {
    // doc
    // exemplo
    // echo dataPrimeiroDiaMes 01/10/2020
    // echo dataPrimeiroDiaMes 2019-10-01
    /**
     * Pega o primeiro dia do mês
     *
     * @param   string      $data       Data a ser usada
     * @param   string      $formato    Formato de retorno da data
     * @return  string|bool             Retorna o primeiro dia do mês
     */
    function dataPrimeiroDiaMes(string $data, string $formato = 'Y-m-d'): string|bool
    {
        if (empty($data)) {
            return false;
        }
        return (new \Helpers\DataHelper)->valor($data)->primeiroDiaMes()->r($formato);
    }
}
