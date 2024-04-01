<?php

namespace ORM\Condicao;

use Erro\Erro;
use Where\WhereInterface;
use Status\StatusInterface;
use Modules\ModuleInterface;

trait CondicaoTrait
{
    private function ormCondicao(array|WhereInterface $dado, bool $obrigatorio = true, string $separador = 'AND', string $tipo = '', ?array $replace = null)
    {
        $dado = $dado instanceof WhereInterface ? $dado->where() : $dado;
        if (empty($dado) && $obrigatorio) {
            throw new Erro(
                mensagem: 'Você precisa passar alguma condição para o filtro.'
            );
        } elseif (empty($dado)) {
            return $this;
        }

        $replace = is_array($replace) ? array_flip($replace) : array_flip($this->pegarReplace());

        $propriedade = 'orm' . ucfirst($tipo) . 'Dado';
        $this->ormAdicionarAndACondicao($tipo);
        $this->$propriedade[] = $this->ormMontarGrupoParaCondicao($dado, $separador, $tipo, $replace);
    }

    private function ormCondicaoTexto(string $dado, array $valor, bool $obrigatorio = true, string $tipo = '', ?array $replace = null)
    {
        if (empty($dado) && $obrigatorio) {
            throw new Erro(
                mensagem: 'Você precisa passar alguma condição para o filtro.'
            );
        } elseif (empty($dado)) {
            return $this;
        } elseif (!strstr($dado, '?')) {
            throw new Erro(
                mensagem: 'Você deve passar os valores como "?".'
            );
        }
        $propriedadeDado = 'orm' . ucfirst($tipo) . 'Dado';

        $lista = explode('?', $dado);
        $quantidade = count($lista);
        $query = '';
        for ($i = 0; $i < $quantidade; $i++) {
            if ($i < $quantidade - 1) {
                $this->ormCondicaoNumero++;
                $numero = 'db_' . $this->ormCondicaoNumero;
                $query .= $lista[$i] . ':' . $numero;
                $this->ormCondicaoValue[$numero] = $this->ormPegarValorCondicaoReal($valor[$i]);
                continue;
            }
            $query .= $lista[$i];
        }

        $this->ormAdicionarAndACondicao($tipo);
        $this->$propriedadeDado[] = $query;
    }

    private function ormAdicionarAndACondicao(string $tipo)
    {
        $campo = 'orm' . ucfirst($tipo) . 'Dado';
        $dado = $this->$campo;
        if ($dado && (!is_string(end($dado)) || !preg_match('/\ {0,} elseif (OR|AND)\ {0,}$/', end($dado)))) {
            $this->$campo[] = ' AND';
        }
    }

    private function ormMontarGrupoParaCondicao(array $dado, string $separador, string $tipo, ?array $replace): array
    {
        $separador = mb_strtoupper($separador, 'UTF-8');
        $separadorLista = ['OR', 'AND'];
        if (
            isset($dado[0], $dado[1]) &&
            is_string($dado[0]) &&
            !is_array($dado[1])
        ) {
            $dado = [$dado];
        }

        $separadorTemporario = $separador;
        if (is_string($dado[0]) && in_array($dado[0], $separadorLista)) {
            $separadorTemporario = $dado[0];
            unset($dado[0]);
            $dado = array_values($dado);
        }

        $quantidade = count($dado);
        $array = [];
        for ($i = 0; $i < $quantidade; $i++) {
            $linha = $dado[$i];
            $quantidadeCondicao = is_array($linha) ? count($linha) : 0;

            if (
                is_array($linha[0]) ||
                (is_string($linha[0]) && in_array($linha[0], $separadorLista) && isset($linha[1]) && is_array($linha[1]))
            ) {
                $array[] = $this->ormMontarGrupoParaCondicao($linha, $separador, $tipo, $replace);
            } elseif (
                $quantidadeCondicao == 2 &&
                is_string($linha[0]) &&
                in_array($linha[1], $this->ormCondicaoNull)
            ) {
                $array[] = $this->ormMontarLinhaDaCondicao(campo: $linha[0], condicao: $linha[1], tipo: $tipo, replace: $replace);
            } elseif (
                $quantidadeCondicao == 2 &&
                is_string($linha[0])
            ) {
                $array[] = $this->ormMontarLinhaDaCondicao(campo: $linha[0], condicao: '=', valor: $linha[1], tipo: $tipo, replace: $replace);
            } elseif (
                $quantidadeCondicao == 3 &&
                is_string($linha[0])
            ) {
                $array[] = $this->ormMontarLinhaDaCondicao(campo: $linha[0], condicao: $linha[1], valor: $linha[2], tipo: $tipo, replace: $replace);
            }
            if ($i < $quantidade - 1) {
                $array[] = $separadorTemporario;
            }
        }
        return $array;
    }

    private function ormMontarLinhaDaCondicao(
        string $campo,
        string $condicao,
        $valor = '',
        string $tipo = '',
        ?array $replace = null
    ) {
        $condicaoLista = $this->ormCondicao;
        $condicao = str_replace(' ', '', mb_strtolower($condicao, 'UTF-8'));

        if (!in_array($tipo, ['where', 'having'])) {
            throw new Erro(
                mensagem: 'O tipo ' . $tipo . ' não é um valor válido.'
            );
        }

        if (!in_array($condicao, $condicaoLista)) {
            throw new Erro(
                mensagem: 'A condição ' . $condicao . ' não é um valor permitido.'
            );
        }

        if ($replace && array_key_exists($campo, $replace)) {
            $campo = $replace[$campo];
        }

        if (in_array($condicao, ['null', 'isnull'])) {
            return $this->ormMontaNomeCampo($campo) . ' IS NULL';
        } elseif (in_array($condicao, ['notnull', 'isnotnull', '!null'])) {
            return $this->ormMontaNomeCampo($campo) . ' IS NOT NULL';
        }

        $valor = $this->ormPegarValorCondicaoReal($valor);
        if ($condicao == 'in') {
            if (is_array($valor) && count($valor) > 0) {
                $in = [];
                foreach ($valor as $in_val) {
                    $this->ormCondicaoNumero++;
                    $numero = 'db_' . $this->ormCondicaoNumero;
                    $in[] = $numero;
                    $this->ormCondicaoValue[$numero] = $this->ormPegarValorCondicaoReal($in_val);
                }
                return $this->ormMontaNomeCampo($campo) . ' IN(:' . implode(', :', $in) . ')';
            }
            throw new Erro(
                mensagem: 'Valor do IN incorreto. (' . $campo . ')'
            );
        } elseif ($condicao == 'notin') {
            if (is_array($valor) && count($valor) > 0) {
                $in = [];
                foreach ($valor as $in_val) {
                    $this->ormCondicaoNumero++;
                    $numero = 'db_' . $this->ormCondicaoNumero;
                    $in[] = $numero;
                    $this->ormCondicaoValue[$numero] = $this->ormPegarValorCondicaoReal($in_val);
                }
                return $this->ormMontaNomeCampo($campo) . ' NOT IN(:' . implode(', :', $in) . ')';
            }
            throw new Erro(
                mensagem: 'Valor do NOT IN incorreto. (' . $campo . ')'
            );
        } elseif ($condicao == 'between') {
            if (is_array($valor) && count($valor) == 2) {
                $this->ormCondicaoNumero++;
                $numero1 = 'db_' . $this->ormCondicaoNumero;
                $this->ormCondicaoNumero++;
                $numero2 = 'db_' . $this->ormCondicaoNumero;

                $this->ormCondicaoValue[$numero1] = $this->ormPegarValorCondicaoReal($valor[0]);
                $this->ormCondicaoValue[$numero2] = $this->ormPegarValorCondicaoReal($valor[1]);
                if ($this->validarData($valor[0]) && $this->validarData($valor[1])) {
                    return $this->ormMontaNomeCampo($campo) . ' BETWEEN DATE(:' . $numero1 . ') AND DATE(:' . $numero2 . ')';
                } else {
                    return $this->ormMontaNomeCampo($campo) . ' BETWEEN :' . $numero1 . ' AND :' . $numero2;
                }
            }
            throw new Erro(
                mensagem: 'Valor do BETWEEN incorreto. (' . $campo . ')'
            );
        } elseif ($condicao == 'notbetween') {
            if (is_array($valor) && count($valor) == 2) {
                $this->ormCondicaoNumero++;
                $numero1 = 'db_' . $this->ormCondicaoNumero;
                $this->ormCondicaoNumero++;
                $numero2 = 'db_' . $this->ormCondicaoNumero;

                $this->ormCondicaoValue[$numero1] = $this->ormPegarValorCondicaoReal($valor[0]);
                $this->ormCondicaoValue[$numero2] = $this->ormPegarValorCondicaoReal($valor[1]);
                if ($this->validarData($valor[0]) && $this->validarData($valor[1])) {
                    return $this->ormMontaNomeCampo($campo) . ' NOT BETWEEN DATE(:' . $numero1 . ') AND DATE(:' . $numero2 . ')';
                } else {
                    return $this->ormMontaNomeCampo($campo) . ' NOT BETWEEN :' . $numero1 . ' AND :' . $numero2;
                }
            }
            throw new Erro(
                mensagem: 'Valor do NOT BETWEEN incorreto. (' . $campo . ')'
            );
        } elseif ($condicao == 'json') {
            $this->ormCondicaoNumero++;
            $numero = 'db_' . $this->ormCondicaoNumero;
            $this->ormCondicaoValue[$numero] = $this->ormPegarValorJson($valor);
            return 'JSON_CONTAINS(' . $this->ormMontaNomeCampo($campo) . ', :' . $numero . ', \'' . $this->ormPegarIndiceJson($campo) . '\')';
        } elseif ($condicao == 'like' and is_string($valor)) {
            $this->ormCondicaoNumero++;
            $numero = 'db_' . $this->ormCondicaoNumero;
            $this->ormCondicaoValue[$numero] = $valor;
            return $this->ormMontaNomeCampo($campo) . ' LIKE :' . $numero;
        } elseif ($condicao == 'notlike' and is_string($valor)) {
            $this->ormCondicaoNumero++;
            $numero = 'db_' . $this->ormCondicaoNumero;
            $this->ormCondicaoValue[$numero] = $valor;
            return $this->ormMontaNomeCampo($campo) . ' NOT LIKE :' . $numero;
        } elseif (!is_array($valor)) {
            $this->ormCondicaoNumero++;
            $numero = 'db_' . $this->ormCondicaoNumero;
            $this->ormCondicaoValue[$numero] = $valor;
            return $this->ormMontaNomeCampo($campo) . ' ' . $condicao . ' :' . $numero;
        }
        throw new Erro(
            mensagem: 'Verifique a condição informada. (' . $campo . ')'
        );
    }

    private function ormPegarValorJson($valor)
    {
        if (is_int($valor)) {
            return $valor;
        }
        return '"' . str_replace('"', '', $valor) . '"';
    }

    private function ormPegarIndiceJson($indice)
    {
        $explode = explode('.', $indice);
        unset($explode[0]);
        if (empty($explode)) {
            return '$';
        }
        return '$.' . implode('.', $explode);
    }

    private function ormPegarValorCondicaoReal($valor)
    {
        if ($valor instanceof StatusInterface) {
            return $valor->numero();
        } elseif ($valor instanceof ModuleInterface) {
            return $valor->banco();
        }
        return $valor;
    }

    private function ormMontaNomeCampo($campo)
    {
        if (str_contains($campo, '`') || str_contains($campo, '(')) {
            return $campo;
        }
        $explode = explode('.', $campo);
        return '`' . $this->ormTabelaAtual . '`.`' . $explode[0] . '`';
    }

    private function ormConverterCondicaoParaString($dado, $parente = false)
    {
        if (!$dado) {
            return '';
        }
        $retorno = [];
        foreach ($dado as $linha) {
            if (is_array($linha)) {
                $retorno[] = $this->ormConverterCondicaoParaString($linha, true);
                continue;
            }
            $retorno[] = $linha;
        }
        $string = implode(' ', $retorno);
        if ($parente) {
            $string = '(' . $string . ')';
        }
        return $string;
    }
}
