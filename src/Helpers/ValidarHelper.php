<?php

namespace Helpers;

use Modules\Cpf;
use Erro\Excecao;
use Modules\Cnpj;
use Modules\Data;
use Modules\Email;
use Modules\DataHora;
use Modules\Telefone;
use Status\StatusInterface;
use Modules\ModuleInterface;

final class ValidarHelper
{
    /**
     * @param Mixed     $valor   Valor a ser validado
     * @param Array     $dado    Array com lista de dados a serem verificados
     */
    public function __construct(
        private $valor = null,
        private string $mensagem = '',
        private string $campo = '',
        private array $dado = []
    ) {
    }

    /**
     * @param Mixed         $valor      Valor a ser verificado
     * @param Null|String   $campo      Nome do campo a ser verificado
     * @param Null|String   $mensagem   Mensagem caso ocorra um erro
     */
    public function valor($valor, string $campo = null, string $mensagem = null)
    {
        if ($this->dado) {
            $valor = $this->dado[$valor] ?? '';
        }
        $this->valor = $valor;
        $this->campo = !empty($campo) ? $campo : '';
        $this->mensagem = !empty($mensagem) ? $mensagem : '';
        return $this;
    }

    private function setarErro(int $codigo): Excecao
    {
        throw new Excecao(mensagem: $this->mensagem, codigo: $codigo, campo: $this->campo, status: 400);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDAR VIA STRING
    |--------------------------------------------------------------------------
    */

    /**
     * Valida dados em formato de string
     * @param String $dado      Dado com os campos a serem validados seguindo o padrão:
     *                          campo|Nome do campo ou !Mensagem|comando 01|comando 02.
     *                          Fora todos os campos normais, existe os "mágicos" a seguir:
     *                          inArray:01=Campo01,02=campo02,03=campo03 ou Campo01,campo02,campo03 = arrayString(['Campo 01', ...])
     *                          arrayKey:Igual o inArray e verifica se valor existe na chave do array
     *                          (string|numeric|array|int|decimal|float)?((> | >= | = | == | != | < | <= )[0-9]+)? para validar tipo e/ou tamanho
     *                          reg:/^expressao aqui$/
     */
    public function validar(string $dado)
    {
        if (!$dado) {
            return $this;
        }

        $explode = explode(PHP_EOL, $dado);
        $lista = [];
        foreach ($explode as $val) {
            $val = preg_replace(['/\t/'], '', trim($val));
            if (!empty($val)) {
                $lista[] = $val;
            }
        }
        foreach ($lista as $linha) {
            $linha = explode('|', $linha);
            if (count($linha) <= 2) {
                continue;
            }
            $valor = $linha[0];
            $campo = $linha[1];
            $mensagem = '';
            unset($linha[0], $linha[1]);

            if (preg_match("/^\!/", $campo)) {
                $mensagem = preg_replace("/^\!/", '', $campo);
                $campo = '';
            }

            $this->valor($valor, $campo, $mensagem);
            foreach ($linha as $acao) {
                if (preg_match("/^inArray\:/i", $acao)) {
                    $this->inArray(stringArray(preg_replace("/^inArray\:/i", '', $acao)));
                } elseif (preg_match("/^arrayKey\:/i", $acao)) {
                    $this->arrayKey(stringArray(preg_replace("/^arrayKey\:/i", '', $acao)));
                } elseif (preg_match("/^(\>\=|\<\=|\>|\<|\=|\!\=|\=\=)[0-9]+/", $acao)) {
                    $operador = preg_replace("/[^\<\>\=\!]/", '', $acao);
                    $tamanho = preg_replace("/[\<\>\=\!]/", '', $acao);
                    if (!empty($campo)) {
                        $this->valor($valor, '', $this->mensagemTamanhoPersonalizada($campo, $operador, $tamanho, 'string'));
                    }
                    $this->tamanho($operador, $tamanho, 'string');
                } elseif (preg_match("/^(string|numeric|array)(\>\=|\<\=|\>|\<|\=|\!\=|\=\=)[0-9]/i", $acao)) {
                    $tipo = preg_replace('/[^string|numeric|array]/i', '', $acao);
                    $acao = preg_replace('/^(string|numeric|array)/i', '', $acao);
                    $operador = preg_replace("/[^\<\>\=\!]/", '', $acao);
                    $tamanho = preg_replace("/[\<\>\=\!]/", '', $acao);
                    if (!empty($campo)) {
                        $this->valor($valor, '', $this->mensagemTamanhoPersonalizada($campo, $operador, $tamanho, $tipo));
                    }
                    $this->tamanho($operador, $tamanho, $tipo);
                } elseif (
                    preg_match(
                        "/^(inteiro|isInt|int|decimal|float|isNumeric|numeric|numero)(\>\=|\<\=|\>|\<|\=|\!\=|\=\=)[0-9]/i",
                        $acao
                    )
                ) {
                    $tipo = preg_replace('/[^inteiro|isInt|int|decimal|float|isNumeric|numeric|numero]/i', '', $acao);
                    $acao = preg_replace('/^(inteiro|isInt|int|decimal|float|isNumeric|numeric|numero)/i', '', $acao);
                    $this->$tipo();
                    $operador = preg_replace("/[^\<\>\=\!]/", '', $acao);
                    $tamanho = preg_replace("/[\<\>\=\!]/", '', $acao);
                    if (!empty($campo)) {
                        $this->valor($valor, '', $this->mensagemTamanhoPersonalizada($campo, $operador, $tamanho, 'numeric'));
                    }
                    $this->tamanho($operador, $tamanho, 'numeric');
                } elseif (preg_match("/^reg\:/i", $acao)) {
                    $this->reg('"' . preg_replace("/^reg\:/i", '', $acao) . '"');
                } else {
                    $this->$acao();
                }
            }
        }
        return $this;
    }

    private function mensagemTamanhoPersonalizada($campo, $operador, $tamanho, $tipo = 'string')
    {
        $valor = $this->valor;
        if (empty($tipo) && is_numeric($valor)) {
            $tipo = 'numeric';
        } elseif (empty($tipo) && is_array($valor)) {
            $tipo = 'array';
        }
        $caracterTexto = 'caracteres';
        $indiceTexto = 'indices';
        if ($tamanho <= 1) {
            $caracterTexto = 'caracter';
            $indiceTexto = 'indice';
        }
        $operador = $operador == '==' ? '=' : $operador;
        $lista = [
            'string' => [
                '<=' => 'O campo ' . $campo . ' deve ter ' . $tamanho . ' ' . $caracterTexto . ' ou menos.',
                '<' => 'O campo ' . $campo . ' deve ter menos de ' . $tamanho . ' ' . $caracterTexto . '.',
                '>=' => 'O campo ' . $campo . ' deve ter ' . $tamanho . ' ' . $caracterTexto . ' ou mais.',
                '>' => 'O campo ' . $campo . ' deve ter mais de ' . $tamanho . ' ' . $caracterTexto . '.',
                '=' => 'O campo ' . $campo . ' deve ter exatamente ' . $tamanho . ' ' . $caracterTexto . '.',
                '!=' => 'O campo ' . $campo . ' não pode ter ' . $tamanho . ' ' . $caracterTexto . '.',
            ],
            'numeric' => [
                '<=' => 'O campo ' . $campo . ' deve ser menor ou igual a ' . $tamanho . '.',
                '<' => 'O campo ' . $campo . ' deve ser menor que ' . $tamanho . '.',
                '>=' => 'O campo ' . $campo . ' deve ser maior ou igual a ' . $tamanho . '.',
                '>' => 'O campo ' . $campo . ' deve ser maior que ' . $tamanho . '.',
                '=' => 'O campo ' . $campo . ' deve ser igual a' . $tamanho . '.',
                '!=' => 'O campo ' . $campo . ' deve ser diferente de ' . $tamanho . '.',
            ],
            'array' => [
                '<=' => 'O campo ' . $campo . ' deve ter ' . $tamanho . ' ' . $indiceTexto . ' ou menos.',
                '<' => 'O campo ' . $campo . ' deve ter menos de ' . $tamanho . ' ' . $indiceTexto . '.',
                '>=' => 'O campo ' . $campo . ' deve ter ' . $tamanho . ' ' . $indiceTexto . ' ou mais.',
                '>' => 'O campo ' . $campo . ' deve ter mais de ' . $tamanho . ' ' . $indiceTexto . '.',
                '=' => 'O campo ' . $campo . ' deve ter exatamente ' . $tamanho . ' ' . $indiceTexto . '.',
                '!=' => 'O campo ' . $campo . ' não pode ter ' . $tamanho . ' ' . $indiceTexto . '.',
            ]
        ];
        return $lista[$tipo][$operador] ?? 'O campo ' . $campo . ' não contem o tamanho/caracteres desejado.';
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFICA SE O VALOR É VAZIO OU OBRIGATÓRIO
    |--------------------------------------------------------------------------
    */
    public function vazio()
    {
        $classe = $this->valor instanceof ModuleInterface || $this->valor instanceof StatusInterface;
        if (false === $this->valor) {
            return $this;
        } elseif (
            ($classe && $this->valor->vazio()) ||
            (!$classe && empty($this->valor))
        ) {
            $this->setarErro(900);
        }
        return $this;
    }

    public function obrigatorio(): self
    {
        if (false === $this->valor) {
            $this->setarErro(919);
        }
        return $this;
    }

    public function valido(): self
    {
        $classe = $this->valor instanceof ModuleInterface || $this->valor instanceof StatusInterface;
        if ($classe && !$this->valor->vazio() && !$this->valor->valido()) {
            $this->setarErro(930);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SE É UMA URL
    |--------------------------------------------------------------------------
    */
    public function url(): self
    {
        $valor = $this->valor;
        if (!empty($valor) && !filter_var($valor, FILTER_VALIDATE_URL)) {
            $this->setarErro(924);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SE É UM NÚMERO INTEIRO
    |--------------------------------------------------------------------------
    */
    public function int(): self
    {
        return $this->isInt();
    }

    public function inteiro(): self
    {
        return $this->isInt();
    }

    public function isInt(): self
    {
        $valor = $this->valor;
        if (!empty($valor) && !filter_var($valor, FILTER_VALIDATE_INT)) {
            $this->setarErro(901);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | BOOL
    |--------------------------------------------------------------------------
    */
    public function true(): self
    {
        $this->isTrue();
        return $this;
    }
    public function isTrue(): self
    {
        $valor = $this->valor;
        if ($valor === true || ($valor !== false && empty($valor))) {
            return $this;
        }
        $this->setarErro(928);
    }
    public function false(): self
    {
        $this->isFalse();
        return $this;
    }
    public function isFalse(): self
    {
        $valor = $this->valor;
        if (empty($valor)) {
            return $this;
        }
        $this->setarErro(929);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDA OS NUMEROS FLUTUANTES
    |--------------------------------------------------------------------------
    */
    public function decimal(): self
    {
        return $this->isDecimal();
    }

    public function isDecimal(): self
    {
        $valor = $this->valor;
        if (!empty($valor) && !preg_match("/^[0-9]*\.[0-9]{1,}$/", $valor)) {
            $this->setarErro(902);
        }
        return $this;
    }

    public function float(): self
    {
        return $this->isFloat();
    }

    public function isFloat(): self
    {
        $valor = $this->valor;
        if (
            !empty($valor) &&
            (!filter_var($valor, FILTER_VALIDATE_FLOAT) || !strstr($valor, '.') || substr($valor, -1) == '.')
        ) {
            $this->setarErro(903);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SE É UM NUMERO
    |--------------------------------------------------------------------------
    */
    public function numero(): self
    {
        return $this->isNumeric();
    }

    public function numeric(): self
    {
        return $this->isNumeric();
    }

    public function isNumeric(): self
    {
        $valor = $this->valor;
        if (!empty($valor) && !is_numeric($valor)) {
            $this->setarErro(904);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SE É UM TEXTO
    |--------------------------------------------------------------------------
    */
    public function texto(): self
    {
        return $this->isString();
    }

    public function string(): self
    {
        return $this->isString();
    }

    public function isString(): self
    {
        $valor = $this->valor;
        if (!empty($valor) && (!is_string($valor) || is_numeric($valor))) {
            $this->setarErro(922);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SE É UM OBJECTO
    |--------------------------------------------------------------------------
    */
    public function object(): self
    {
        return $this->isObject();
    }

    public function objeto(): self
    {
        return $this->isObject();
    }

    public function isObject(): self
    {
        $valor = $this->valor;
        if (!vazio($valor) && !is_object($valor)) {
            $this->setarErro(923);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SE O VALOR É BOOL
    |--------------------------------------------------------------------------
    */
    public function bool(): self
    {
        return $this->isBool();
    }

    public function booleano(): self
    {
        return $this->isBool();
    }

    public function isBool(): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $valor = $this->valor;
        if (
            !empty($valor) &&
            (
                (true !== $valor && 'true' != $valor) &&
                (false !== $valor && 'false' != $valor) &&
                (!is_numeric($valor) || !in_array($valor, [0, 1]))
            )
        ) {
            $this->setarErro(921);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SE É UM JSON
    |--------------------------------------------------------------------------
    */
    public function json(): self
    {
        return $this->isJson();
    }

    public function isJson(): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $valor = $this->valor;
        if (!empty($valor) && !is_array(jsonDecode($valor, true))) {
            $this->setarErro(914);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | MANUPULA OS ARRAIES
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica se existe um valor no array = in_array
     * @param Array     $array  Array que deseja comparar
     */
    public function inArray(array $array): self
    {
        $valor = $this->valor;
        if (!empty($valor) && !in_array($valor, $array)) {
            $this->setarErro(905);
        }
        return $this;
    }

    /**
     * Verifica se existe uma chave no array = array_key_exists
     * @param Array     $array  Array que deseja comparar
     */
    public function arrayKey(array $array): self
    {
        $valor = $this->valor;
        if (!empty($valor) && !array_key_exists($valor, $array)) {
            $this->setarErro(905);
        }
        return $this;
    }

    public function array(): self
    {
        $this->isArray();
        return $this;
    }

    public function isArray(): self
    {
        $valor = $this->valor;
        if (!empty($valor) && !is_array($valor)) {
            $this->setarErro(910);
        }
        return $this;
    }

    /**
     * Se o valor é diferente do array = array_diff
     * @param Array     $array  Array que deseja comparar
     */
    public function diff(array $array): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $diff = array_diff(array_keys($this->valor), $array);
        if ($diff) {
            $this->campo = implode(', ', $diff);
            $this->setarErro(920);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SE É UM TELEFONE
    |--------------------------------------------------------------------------
    */
    public function telefone(): self
    {
        $valor = $this->valor;
        $valor = $valor instanceof Telefone ? $valor : new Telefone($valor);

        if (!$valor->vazio() && !$valor->valido()) {
            $this->setarErro(908);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDA SE É UM EMAIL
    |--------------------------------------------------------------------------
    */
    public function email(): self
    {
        $valor = $this->valor;
        $valor = $valor instanceof Email ? $valor : new Email($valor);

        if (!$valor->vazio() && !$valor->valido()) {
            $this->setarErro(909);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDA SE É IGUAL OU DIFERENTE
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica se o valor é igual ao comparado
     * @param Mixed     $comparacao         Valor a ser comparado
     * @param Bool      $exato              True para validar o tipo (===)
     */
    public function igual($comparacao, bool $exato = true): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $valor = $this->valor;
        if (
            ($exato == true && $valor !== $comparacao) ||
            ($exato == false && $valor != $comparacao)
        ) {
            $this->setarErro(912);
        }
        return $this;
    }

    /**
     * Verifica se o valor é diferente ao comparado
     * @param Mixed     $comparacao         Valor a ser comparado
     * @param Bool      $exato              True para validar o tipo (===)
     */
    public function diferente($comparacao, bool $exato = true): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $valor = $this->valor;
        if (
            ($exato == true && $valor === $comparacao) ||
            ($exato == false && $valor == $comparacao)
        ) {
            $this->setarErro(912);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDAR O TAMANHO
    |--------------------------------------------------------------------------
    */
    /**
     * Valida o tamanho de uma string ou valores numericos
     *
     * @param string            $operador       Qual o operador será usado podendo ser: >=, >, <=, <, == ou !=
     * @param null|int|string   $comparador     O tamanho que o número ou string deve ter ou o texto para comparar
     * @param null|string       $tipo           Tipo de dado a ser comparado podendo ser texto, numero, data ou hora
     */
    public function tamanho(string $operador, null|int|string $comparador, ?string $tipo = null): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $valor = $this->valor;
        if (!empty($tipo) && in_array($tipo, ['string', 'texto'])) {
            $strlen = mb_strlen((string) $valor, 'UTF-8');
        } elseif ((!empty($tipo) && in_array($tipo, ['numeric', 'numero'])) || is_numeric($valor)) {
            $strlen = $valor;
        } elseif ((!empty($tipo) && in_array($tipo, ['data', 'date'])) || $this->eUmaData($valor)) {
            try {
                $strlen = (new \DateTime(str_replace('/', '-', $valor)))->format('YmdHis');
                $comparador = (new \DateTime(str_replace('/', '-', $comparador)))->format('YmdHis');
            } catch (\Throwable) {
                $this->setarErro(907);
                return $this;
            }
        } elseif ((!empty($tipo) && in_array($tipo, ['time', 'hora'])) || $this->eUmaHora($valor)) {
            try {
                $strlen = (new \DateTime($valor))->format('His');
                $comparador = (new \DateTime($comparador))->format('His');
            } catch (\Throwable) {
                $this->setarErro(907);
                return $this;
            }
        } elseif (is_string($valor)) {
            $strlen = mb_strlen($valor, 'UTF-8');
        } elseif (is_array($valor)) {
            $strlen = count($valor);
        }
        if (!empty($valor) && (!in_array($operador, ['>=', '>', '=', '==', '!=', '<=', '<']) ||
            ($operador == '>=' && $strlen < $comparador) ||
            ($operador == '>' && $strlen <= $comparador) ||
            (in_array($operador, ['=', '==']) && $strlen != $comparador) ||
            ($operador == '!=' && $strlen == $comparador) ||
            ($operador == '<=' && $strlen > $comparador) ||
            ($operador == '<' && $strlen >= $comparador)
        )) {
            $this->setarErro(907);
        }
        return $this;
    }
    private function eUmaData($data)
    {
        return preg_match('/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $data) ||
            preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})$/', $data) ||
            preg_match(
                "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\ ([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/",
                $data
            ) ||
            preg_match(
                "/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}\ ([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/",
                $data
            );
    }
    private function eUmaHora($hora)
    {
        return preg_match("/^([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/", $hora) ||
            preg_match("/^([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9])$/", $hora);
    }

    /*
    |--------------------------------------------------------------------------
    | SE É UMA DATA
    |--------------------------------------------------------------------------
    */
    public function hoje(): self
    {
        $valor = $this->valor;
        $valor = $valor instanceof Data ? $valor->date() : (new Data($valor))->date();

        if (!empty($valor) && $valor != date('Y-m-d')) {
            $this->setarErro(931);
        }

        return $this;
    }
    public function date(): self
    {
        $valor = $this->valor instanceof Data ? $this->valor : new Data($this->valor);
        if (!$valor->vazio() && (!$valor->valido() || !$valor->eDate())) {
            $this->setarErro(915);
        }
        return $this;
    }

    public function data(): self
    {
        $valor = $this->valor instanceof Data ? $this->valor : new Data($this->valor);
        if (!$valor->vazio() && (!$valor->valido() || !$valor->eData())) {
            $this->setarErro(915);
        }
        return $this;
    }

    public function dataDate(): self
    {
        $valor = $this->valor instanceof Data ? $this->valor : new Data($this->valor);
        if (!$valor->vazio() && !$valor->valido()) {
            $this->setarErro(915);
        }
        return $this;
    }

    public function dateTime(): self
    {
        $valor = $this->valor instanceof DataHora ? $this->valor : new DataHora($this->valor);
        if (!$valor->vazio() && (!$valor->valido() || !$valor->eDate())) {
            $this->setarErro(915);
        }
        return $this;
    }

    public function dataHora(): self
    {
        $valor = $this->valor instanceof DataHora ? $this->valor : new DataHora($this->valor);
        if (!$valor->vazio() && (!$valor->valido() || !$valor->eData())) {
            $this->setarErro(915);
        }
        return $this;
    }

    public function dataDateTime(): self
    {
        $valor = $this->valor instanceof DataHora ? $this->valor : new DataHora($this->valor);
        if (!$valor->vazio() && !$valor->valido()) {
            $this->setarErro(915);
        }
        return $this;
    }

    /**
     * Validar a hora no formato 00:00:00
     */
    public function hora(): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $valor = $this->valor;
        if (
            !empty($valor) &&
            !preg_match(
                "/^([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/",
                $valor
            )
        ) {
            $this->setarErro(915);
        }
        return $this;
    }

    /**
     * Valida a hora no formato 00:00
     */
    public function horaMinuto(): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $valor = $this->valor;
        if (
            !empty($valor) &&
            !preg_match(
                "/^([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9])$/",
                $valor
            )
        ) {
            $this->setarErro(915);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDA SE É DOCUMENTO
    |--------------------------------------------------------------------------
    */
    public function cpf(): self
    {
        $valor = $this->valor;
        $valor = $valor instanceof Cpf ? $valor : new Cpf($valor);

        if (!$valor->vazio() && !$valor->valido()) {
            $this->setarErro(926);
        }
        return $this;
    }

    public function cnpj(): self
    {
        $valor = $this->valor;
        $valor = $valor instanceof Cnpj ? $valor : new Cnpj($valor);

        if (!$valor->vazio() && !$valor->valido()) {
            $this->setarErro(927);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDA SE É POSITIVO OU NEGATIVO
    |--------------------------------------------------------------------------
    */
    public function positivo(): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $valor = $this->valor;
        if (!empty($valor) && (!is_numeric($valor) || $valor < 0)) {
            $this->setarErro(917);
        }
        return $this;
    }

    public function negativo(): self
    {
        if (empty($this->valor)) {
            return $this;
        }
        $valor = $this->valor;
        if (!empty($valor) && (!is_numeric($valor) || $valor >= 0)) {
            $this->setarErro(918);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDA EXPRESSÃO REGULAR
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica se o valor bate com uma expressão regular
     * @param String    $expressao      Expressão regular a ser verificada
     */
    public function reg(string $expressao): self
    {
        $valor = $this->valor;
        if (!preg_match($expressao, $valor)) {
            $this->setarErro(925);
        }
        return $this;
    }
}
