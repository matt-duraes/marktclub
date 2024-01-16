<?php

namespace ORM\Salvar;

use Erro\Erro;
use Erro\Excecao;
use Modules\Senha;
use Modules\Vazio;

trait SalvarTrait
{
    private string $ormAcao;

    public function salvar()
    {
        $this->ormVerificarSeEntityExiste();
        $this->ormPegarAcaoAoSalvar();

        if ($this->ormAcao == 'insert' && method_exists($this, 'regraInsert')) {
            $this->regraInsert();
        } elseif ($this->ormAcao == 'update' && method_exists($this, 'regraUpdate')) {
            $this->regraUpdate();
        }

        if ($this->cancelarSalvar) {
            $this->cancelarSalvar = false;
            return;
        }

        if (method_exists($this, 'regraSalvar')) {
            $this->regraSalvar();
        }

        if ($this->cancelarSalvar) {
            $this->cancelarSalvar = false;
            return;
        }

        $this->ormPegarAcaoAoSalvar();

        $dado = $this->ormMontarDado();

        $this->ormValidarViaHelper($this->ormAcao);

        $deletarArquivo = [];
        if ($this->ormAcao == 'insert') {
            $salvar = $this->dado($dado['salvar'])->insert();
        } elseif ($this->ormAcao == 'update' && empty($dado['salvar'])) {
            $salvar = ['id' => $this->prop('id')];
        } elseif ($this->ormAcao == 'update') {
            $deletarArquivo = $this->ormPegarArquivoParaDeletar($dado['salvar']);
            $salvar = $this->dado($dado['salvar'])->where(['id', $this->ormEntityId])->update();
        }
        if (is_array($salvar) && array_key_exists('id', $salvar)) {
            $this->buscar(['id', $salvar['id']], leitura: false);
            $this->ormAcaoPosSalvar($this->ormAcao);
            $this->ormDeletarArquivos($deletarArquivo);
            $this->ormDiff = $dado['salvar'];
            return $this;
        }
        throw new Excecao(titulo: 'Erro ao salvar!', mensagem: 'Ocorreu um erro ao salvar, por favor, tente novamente.');
    }

    private function ormPegarAcaoAoSalvar(): void
    {
        $id = $this->ormEntityId;
        if (is_int($id) && $id > 0) {
            $this->ormAcao = 'update';
            return;
        }
        $this->ormAcao = 'insert';
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
        if ($this->ormAcao == 'update') {
            $dadoAtual = $this->ormPegarTodosOsDadoPeloId($this->ormEntityId);
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
                (
                    !$propriedadeReal->valido() ||
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

        if (empty($lista) && $this->ormAcao == 'insert') {
            throw new Excecao(
                titulo: 'Erro ao salvar!',
                mensagem: 'Você precisa passar pelo menos uma informação para salvar.'
            );
        }

        return [
            'todos'  => $listaTodosOsDados,
            'salvar' => $lista
        ];
    }

    private function ormVerificarSePodeSalvarCampo(string $campo, $valor, array $lista = [])
    {
        $acao = $this->ormAcao;
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
        $parametro = property_exists($this, 'ormSalvar') && is_array($this->ormSalvar) && $this->ormSalvar ? $this->ormSalvar : [];
        if ($this->ormAcao == 'insert' && !empty($this->ormInsert)) {
            $parametro = array_merge($parametro, $this->ormInsert);
        } elseif ($this->ormAcao == 'update' && !empty($this->ormUpdate)) {
            $parametro = array_merge($parametro, $this->ormUpdate);
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
