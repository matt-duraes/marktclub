<?php

namespace System\Trait\Model;

use Http\Request;

trait WhereTrait
{
    private array $where = [];
    private Request $request;

    /**
     * Seta um where manualmente
     *
     * @param  array $where Array com o where que deseja setar
     * @return self
     */
    private function setarWhereManual(array $where): self
    {
        $this->where[] = $where;
        return $this;
    }

    private function setarWhere(
        string $campo,
        string $condicao = '=',
        ?string $tipo = null,
        bool $obrigatorio = false,
        bool $vazio = true,
        bool $valido = true
    ) {
        $this->validarWhere($campo, $tipo, $obrigatorio, $vazio, $valido);
        $this->setarCampoWhere($campo, $condicao, $tipo);
        return $this;
    }

    private function pegarWhere()
    {
        return $this->where;
    }

    private function validarWhere(
        string $campo,
        ?string $tipo,
        bool $obrigatorio,
        bool $vazio,
        bool $valido
    ) {
        $campoExite = $this->request->existe($campo);
        $campoVazio = $this->request->vazio($campo);
        if (!$campoExite && $obrigatorio) {
            mensagemErro('Campo obrigatório', 'O campo ' . $campo . ' é obrigatório.');
        } elseif ($campoVazio && !$vazio) {
            mensagemErro('Campo obrigatório', 'O campo ' . $campo . ' é obrigatório.');
        }

        $valor = $this->request->$campo;
        $this->validarTipoValor($campo, $valor, $tipo, $valido);
    }

    private function validarTipoValor(string $campo, $valor, string $tipo, bool $valido)
    {
        if (empty($tipo) || empty($valor) || !$valido) {
            return;
        }

        $valorValidado = false;
        $tipo = strCaixaAltaAlta($tipo);
        if (function_exists('validar' . $tipo)) {
            $funcao = 'validar' . $tipo;
            $valorValidado = $funcao($valor);
        }
        if ($valorValidado) {
            mensagemErro('Campo inválido!', 'O campo ' . $campo . ' não é um valor válido.');
        }
    }

    private function setarCampoWhere($campo, $condicao, $tipo)
    {
        if ($this->request->vazio($campo)) {
            return;
        }

        $valor = $this->pegarValorConvertido($campo, $tipo);

        $where = [$campo, $condicao, $this->request->$campo];
        if (in_array($condicao, ['null', 'isnull', '!null', 'notnull'])) {
            $where = [$campo, $condicao];
        } elseif (in_array($condicao, ['in', '!in', 'notin', 'between', '!between', 'notbetween'])) {
            $valor = !is_array($valor) ? jsonDecode($valor, true, true) : [];
            $where = !empty($valor) ? [$campo, $condicao, $valor] : '';
        }

        if (!empty($where)) {
            $this->where[] = $where;
        }
    }

    private function pegarValorConvertido($campo, $tipo)
    {
        $valor = $this->request->$campo;

        if (in_array($tipo, ['cpf', 'telefone', 'cnpj'])) {
            return preg_replace('/[^0-9]/', '', $valor);
        } elseif (in_array($tipo, ['data', 'date'])) {
            return dataBanco($valor);
        } elseif (in_array($tipo, ['dataHora', 'dateTime'])) {
            return dataHoraBanco($valor);
        }

        return $valor;
    }
}
