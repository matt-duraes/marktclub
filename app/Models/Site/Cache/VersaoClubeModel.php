<?php

namespace App\Models\Site\Cache;

final class VersaoClubeModel
{
    private string $id;

    public const RETORNO_ARRAY = 'array';
    public const RETORNO_OBJECT = 'object';
    public const RETORNO_STRING = 'string';
    public const RETORNO_NUMERO = 'numero';
    public const RETORNO_INT = 'int';
    public const RETORNO_FLOAT = 'float';

    public function __construct()
    {
        $this->id = env('CONSTRUTOR_VERSAO', '');
    }

    public function cache(string $indice, mixed $valor = null, mixed $padrao = null, string $retorno = null)
    {
        if (is_null($valor)) {
            return $this->pegarValor($indice, $padrao, $retorno);
        }
        $this->setarValor($indice, $valor);
    }

    public function deletar(string $indice)
    {
        $campo = $indice . '_' . $this->id;
        if (!sessaoExiste($campo)) {
            return;
        }
        return sessaoDeletar($campo);
    }

    private function pegarValor(string $indice, mixed $padrao = null, string $retorno = null)
    {
        $campo = $indice . '_' . $this->id;
        if (!sessaoExiste($campo)) {
            return $this->pegarRetornoPadrao($padrao, $retorno);
        }
        $valor = sessao($campo);
        if (
            ($retorno === self::RETORNO_ARRAY && !is_array($valor)) ||
            ($retorno === self::RETORNO_STRING && !is_string($valor)) ||
            ($retorno === self::RETORNO_NUMERO && !is_numeric($valor)) ||
            ($retorno === self::RETORNO_INT && !is_int($valor)) ||
            ($retorno === self::RETORNO_FLOAT && !is_float($valor)) ||
            ($retorno === self::RETORNO_OBJECT && !is_object($valor))
        ) {
            return $this->pegarRetornoPadrao($padrao, $retorno);
        }
        return $valor;
    }

    private function pegarRetornoPadrao($padrao, $retorno)
    {
        if (is_null($retorno)) {
            return $padrao;
        } elseif ($retorno === self::RETORNO_ARRAY) {
            return is_array($padrao) ? $padrao : [];
        } elseif ($retorno === self::RETORNO_OBJECT) {
            return is_object($padrao) ? $padrao : (object)[];
        } elseif ($retorno === self::RETORNO_STRING) {
            return is_string($padrao) ? $padrao : '';
        } elseif ($retorno === self::RETORNO_NUMERO) {
            return is_numeric($padrao) ? $padrao : 0;
        } elseif ($retorno === self::RETORNO_INT) {
            return is_int($padrao) ? $padrao : 0;
        } elseif ($retorno === self::RETORNO_FLOAT) {
            return is_float($padrao) ? $padrao : 0;
        }
        return $padrao;
    }

    private function setarValor(string $indice, mixed $valor)
    {
        $campo = $indice . '_' . $this->id;
        return sessao($campo, $valor);
    }
}
