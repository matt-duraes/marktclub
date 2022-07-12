<?php

namespace ORM\Salvar;

use Erro\Erro;
use Erro\Excecao;
use Modules\Senha;
use Modules\Vazio;

trait SalvarTrait
{
    private string $_acao;

    public function salvar()
    {
        $this->ormVerificarSeEntityExiste();
        $this->ormPegarAcaoAoSalvar();
        $acao = $this->_acao;
        if ($acao == 'insert' && method_exists($this, 'regraInsert')) {
            $this->regraInsert();
        } elseif ($acao == 'update' && method_exists($this, 'regraUpdate')) {
            $this->regraUpdate();
        }

        if (method_exists($this, 'regraSalvar')) {
            $this->regraSalvar();
        }

        $dado = $this->ormMontarDado();
        $this->ormValidarViaHelper($acao);

        $deletarArquivo = [];
        if ($acao == 'insert') {
            $salvar = $this->dado($dado['salvar'])->insert();
        } elseif ($acao == 'update' && empty($dado['salvar'])) {
            $salvar = ['id' => $this->prop('id')];
        } elseif ($acao == 'update') {
            $deletarArquivo = $this->ormPegarArquivoParaDeletar($dado['salvar']);
            $salvar = $this->dado($dado['salvar'])->where(['id', $this->_entityId])->update();
        }

        if (is_array($salvar) && array_key_exists('id', $salvar)) {
            $this->ormAcaoPosSalvar($acao);
            $this->ormDeletarArquivos($deletarArquivo);
            $this->_id($salvar['id']);
            $this->_diff = $dado['salvar'];
            return $this;
        }
        throw new Excecao(titulo: 'Erro ao salvar!', mensagem: 'Ocorreu um erro ao salvar, por favor, tente novamente.');
    }

    private function ormPegarAcaoAoSalvar(): void
    {
        $id = $this->_entityId;
        if (is_int($id) && $id > 0) {
            $this->_acao = 'update';
            return;
        }
        $this->_acao = 'insert';
    }

    private function ormMontarDadoOutroValor($linha)
    {
        if (is_string($linha) && preg_match('/^[a-zA-Z0-9\_]+\(\)$/', $linha)) {
            return $this->ormMontarDadoFuncao($linha);
        } elseif (is_string($linha) && preg_match('/^\-\>/', $linha)) {
            return $this->ormMontarDadoPropriedade($linha);
        }
        return $linha;
    }

    private function ormMontarDadoFuncao($funcao)
    {
        $funcao = str_replace(['(', ')'], '', $funcao);
        if (function_exists($funcao)) {
            return $funcao();
        }
        return null;
    }

    private function ormMontarDadoPropriedade($propriedade)
    {
        $explode = explode('->', $propriedade);
        unset($explode[0]);

        $quantidade = count($explode);
        $valorTemporario = '';
        for ($i = 1; $i <= $quantidade; $i++) {
            $linha = $explode[$i];
            if ($i == 1) {
                $valorTemporario = $this->ormPegarValorPropriedade($linha, erro: false);
            } elseif (
                is_object($valorTemporario) &&
                preg_match('/^[a-zA-Z0-9\_]+\(\)$/', $linha) &&
                method_exists($valorTemporario, str_replace(['(', ')'], '', $linha))
            ) {
                $valorTemporario = $valorTemporario->$linha();
            } elseif (is_object($valorTemporario)) {
                $valorTemporario = $this->ormPegarValorPropriedade(
                    propriedade: $linha,
                    classe: $valorTemporario,
                    erro: false
                );
            }
            if (is_null($valorTemporario) || $valorTemporario instanceof Vazio) {
                return null;
            }
        }
        return $valorTemporario;
    }

    private function ormMontarDado(): array
    {
        $parametro = $this->ormPegarParametro();
        $dadoAtual = [];
        if ($this->_acao == 'update') {
            $dadoAtual = $this->ormPegarTodosOsDadoPeloId($this->_entityId);
        }

        $listaTodosOsDados = [];
        $lista = [];
        foreach ($parametro as $ind => $val) {
            $indice = preg_replace('/^\!/', '', $ind);
            $valor = preg_replace('/^\!/', '', $val);

            $propriedadeReal = preg_replace('/^\-\>/', '', $val);

            if (is_numeric($indice)) {
                $indice = $valor;
                $valor = $this->ormPegarValorPropriedade(
                    propriedade: $valor,
                    erro: false
                );
            } else {
                $valor = $this->ormMontarDadoOutroValor($valor);
            }

            try {
                $propriedadeReal = $this->$propriedadeReal ?? null;
            } catch (\Throwable) {
                $propriedadeReal = null;
            }
            if (
                $propriedadeReal instanceof Senha &&
                (!$propriedadeReal->valido() ||
                    !$propriedadeReal->mudouSenha() ||
                    $propriedadeReal->mesmaSenha()
                )
            ) {
                continue;
            }

            if (!$valor instanceof Vazio && !is_null($valor) && $this->ormVerificarSePodeSalvarCampo($indice, $valor, $dadoAtual)) {
                $lista[$indice] = $valor;
            }
            $listaTodosOsDados[$indice] = $valor;
        }
        if (empty($lista) && $this->_acao == 'insert') {
            throw new Excecao(
                titulo: 'Erro ao salvar!',
                mensagem: 'Você precisa passar pelo menos uma informação para salvar.'
            );
        }
        return [
            'todos' => $listaTodosOsDados,
            'salvar' => $lista
        ];
    }

    private function ormVerificarSePodeSalvarCampo(string $campo, $valor, array $lista = [])
    {
        $acao = $this->_acao;
        if ($acao == 'insert') {
            return !empty($valor);
        }

        if (!array_key_exists($campo, $lista)) {
            throw new Erro(mensagem: 'O indice ' . $campo . ' não existe no Banco de Dados.');
        }

        $valorAtual = $lista[$campo];
        if (empty($valorAtual) && empty($valor)) {
            return false;
        } elseif (is_array($valor)) {
            $array = jsonDecode($valorAtual, true);
            return $valor != $array;
        }
        return $valorAtual != $valor;
    }

    private function ormPegarParametro(): array
    {
        $parametro = property_exists($this, '_salvar') && is_array($this->_salvar) && $this->_salvar ? $this->_salvar : [];
        if ($this->_acao == 'insert' && !empty($this->_insert)) {
            $parametro = array_merge($parametro, $this->_insert);
        } elseif ($this->_acao == 'update' && !empty($this->_update)) {
            $parametro = array_merge($parametro, $this->_update);
        }
        return $parametro;
    }

    private function ormAcaoPosSalvar(string $acao)
    {
        if ($acao == 'insert' && method_exists($this, 'regraPosInsert')) {
            $this->regraPosInsert();
        } elseif ($acao == 'update' && method_exists($this, 'regraPosUpdate')) {
            $this->regraPosUpdate();
        }
        if (method_exists($this, 'regraPosSalvar')) {
            $this->regraPosSalvar();
        }
    }
}
