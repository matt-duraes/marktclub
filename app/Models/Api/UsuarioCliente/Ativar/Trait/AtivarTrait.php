<?php

namespace App\Models\Api\UsuarioCliente\Ativar\Trait;

use App\Classes\ConstrutorClube\TipoCargo;
use App\Classes\UsuarioCliente\Hash;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use Modules\Cpf;
use Modules\Data;
use Modules\Email;
use Modules\EnderecoCep;
use Modules\EnderecoEstado;
use Modules\EstadoCivil;
use Modules\Genero;
use Modules\Nome;
use Modules\Senha;
use Modules\Telefone;

trait AtivarTrait
{
    private function validarDado()
    {
        if ($this->nome->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } elseif ($this->cpf->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif ($this->data_nascimento->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo data de nascimento é obrigatório.');
        } elseif ($this->genero->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo gênero é obrigatório.');
        } elseif ($this->email_pessoal->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo e-mail pessoal é obrigatório.');
        } elseif ($this->telefone_pessoal->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo telefone pessoal é obrigatório.');
        } elseif ($this->endereco_cep->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo CEP do endereço é obrigatório.');
        } elseif ($this->endereco_estado->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo estado do endereço é obrigatório.');
        } elseif (empty($this->endereco_cidade)) {
            mensagemErro('Campo obrigatório!', 'O campo cidade do endereço é obrigatório.');
        } elseif ($this->senha->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo senha é obrigatório.');
        } elseif ($this->termo->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve aceitar os termos para ativar seu cadastro.');
        }
        if (!$this->nome->valido()) {
            mensagemErro('Campo inválido!', 'O campo nome não é um valor válido.');
        } elseif (!$this->cpf->valido()) {
            mensagemErro('Campo inválido!', 'O campo CPF não é um valor válido.');
        } elseif (!$this->data_nascimento->valido()) {
            mensagemErro('Campo inválido!', 'O campo data de nascimento não é um valor válido.');
        } elseif (!$this->genero->valido()) {
            mensagemErro('Campo inválido!', 'O campo gênero não é um valor válido.');
        } elseif (!$this->email_pessoal->valido()) {
            mensagemErro('Campo inválido!', 'O campo e-mail pessoal não é um valor válido.');
        } elseif (!$this->email_trabalho->vazio() && !$this->email_trabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo e-mail de trabalho não é um valor válido.');
        } elseif (!$this->telefone_pessoal->valido()) {
            mensagemErro('Campo inválido!', 'O campo telefont pessoal não é um valor válido.');
        } elseif (!$this->telefone_trabalho->vazio() && !$this->telefone_trabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo telefone de trabalho não é um valor válido.');
        } elseif (!$this->endereco_cep->vazio() && !$this->endereco_cep->valido()) {
            mensagemErro('Campo inválido!', 'O campo CEP do endereço não é um valor válido.');
        } elseif (!$this->endereco_estado->valido()) {
            mensagemErro('Campo inválido!', 'O campo estado do endereço não é um valor válido.');
        } elseif ($this->senha->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo senha é obrigatório.');
        } elseif (!$this->senha->valido()) {
            mensagemErro('Campo inválido!', 'O campo senha não é um valor válido.');
        } elseif ($this->termo->valor() != Botao::SIM) {
            mensagemErro('Campo inválido!', 'Você deve aceitar os termos para ativar seu cadastro.');
        }
    }

    private function setarPropriedade()
    {
        $dado = $this->request->dado();

        $this->hash = $dado['hash'];
        $this->nome = new Nome($dado['nome']);
        $this->cpf = new Cpf($dado['cpf']);
        $this->genero = new Genero($dado['genero']);
        $this->senha = new Senha($dado['senha']);
        $this->termo = new Botao($dado['termo']);
        $this->data_nascimento = new Data($dado['data_nascimento']);
        $this->estado_civil = new EstadoCivil($dado['estado_civil']);
        $this->grupo = $dado['grupo'];
        $this->email_pessoal = new Email($dado['email_pessoal']);
        $this->email_trabalho = new Email($dado['email_trabalho']);
        $this->telefone_pessoal = new Telefone($dado['telefone_pessoal']);
        $this->telefone_trabalho = new Telefone($dado['telefone_trabalho']);
        $this->endereco_cep = new EnderecoCep($dado['endereco_cep']);
        $this->endereco_logradouro = $dado['endereco_logradouro'];
        $this->endereco_numero = $dado['endereco_numero'];
        $this->endereco_complemento = $dado['endereco_complemento'];
        $this->endereco_bairro = $dado['endereco_bairro'];
        $this->endereco_estado = new EnderecoEstado($dado['endereco_estado']);
        $this->endereco_cidade = $dado['endereco_cidade'];
        $this->trabalho_cargo = new TrabalhoCargo($dado['trabalho_cargo']);

        $tipoCargo = $this->validarTipoCargo();
        if ($tipoCargo->indice() === TipoCargo::NORMAL) {
            $trabalhoCargo = new TrabalhoEmpresa($dado['trabalho_empresa']);
            $this->trabalho_empresa = $trabalhoCargo->numero();
            return;
        }
        $this->trabalho_empresa = $dado['trabalho_empresa'];
    }

    /**
     * @return TipoCargo
     * @throws Excecao
     */
    private function validarTipoCargo(): TipoCargo
    {
        $isNull = empty($this->pegarClube()['tipo_cargo']);
        $TipoCargo = new TipoCargo($isNull ? TipoCargo::NORMAL : $this->pegarClube()['tipo_cargo']);
        if (!$TipoCargo->valido()) {
            mensagemErro(
                'Tipo Cargo inválido!',
                'O Tipo de Cargo do clube não é válido'
            );
        }
        return $TipoCargo;
    }

    /**
     * @return array
     */
    private function pegarClube(): array
    {
        if (empty(TOKEN) || empty(TOKEN['empresa']) || empty(TOKEN['empresa']->id)) {
            return [];
        }
        $ormHelper = new OrmHelper(TABELA_CONSTRUTOR_CLUBE);
        return $ormHelper->pegarUltimoRegistro(['id_admin_empresa', TOKEN['empresa']->id], ['tipo_cargo']);
    }

    /**
     * @param string|int $id
     *
     * @return bool
     */
    private function validarCargoPersonalizado(string|int $id): bool
    {
        $ormHelper = new OrmHelper(TABELA_SITE_CARGO);
        $cargos = $ormHelper->pegarUltimoRegistro([
            ['id', $id],
            ['status', 1]
        ], ['titulo']);
        return empty($cargos);
    }

    private function validarHash()
    {
        if (
            $this->usuario->hash_tipo != Hash::ATIVAR ||
            $this->usuario->hash_data < dataRemover(agora(), 30, 'minutos', 'Y-m-d H:i:s')
        ) {
            mensagemErro('Erro!', 'Seu tempo de ativação encerrou, reinicie o processo de ativação para continuar.');
        }
    }

    private function validarCpf()
    {
        $cpfUsuario = $this->usuario->cpf;
        $cpf = $this->cpf->numero();
        if (validarCpf($cpfUsuario) && $cpf != $cpfUsuario) {
            mensagemErro('Erro!', 'O CPF informado está diferente do CPF do seu registro.');
        }
    }

    private function validarCampoUnico()
    {
        $id = $this->usuario->id;
        $empresa = $this->usuario->id_admin_empresa;
        $emailPessoal = $this->email_pessoal->email();
        $emailTrabalho = $this->email_trabalho->email();
        $cpf = $this->cpf->numero();
        if ($this->existe($this->whereEmail($id, $empresa, $emailPessoal))) {
            mensagemErro('E-mail inválido!', 'O e-mail pessoal já está em uso por outro usuário.');
        } elseif ($this->existe($this->whereEmail($id, $empresa, $emailTrabalho))) {
            mensagemErro('E-mail inválido!', 'O e-mail de trabalho já está em uso por outro usuário.');
        } elseif ($this->existe([
            ['id', '!=', $id],
            ['id_admin_empresa', $empresa],
            ['cpf', $cpf],
        ])) {
            mensagemErro('CPF inválido!', 'O CPF já está em uso por outro usuário.');
        }
    }
}
