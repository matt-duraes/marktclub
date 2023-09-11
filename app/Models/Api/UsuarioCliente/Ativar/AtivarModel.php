<?php

namespace App\Models\Api\UsuarioCliente\Ativar;

use ORM\ORM;
use stdClass;
use Modules\Cpf;
use Http\Request;
use Modules\Data;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Modules\Senha;
use Modules\Genero;
use Modules\Telefone;
use Modules\EnderecoCep;
use Modules\EstadoCivil;
use Modules\EnderecoEstado;
use App\Classes\UsuarioCliente\Hash;

final class AtivarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private string $erroPadrao = 'Ocorreu um erro ao ativar seu usuário, por favor, tente novamente.';
    private stdClass $usuario;
    private string $hash;
    private Nome $nome;
    private Cpf $cpf;
    private Genero $genero;
    private Senha $senha;
    private Botao $termo;
    private Data $data_nascimento;
    private EstadoCivil $estado_civil;
    private Email $email_pessoal;
    private Email $email_trabalho;
    private Telefone $telefone_pessoal;
    private Telefone $telefone_trabalho;
    private EnderecoCep $endereco_cep;
    private string $endereco_logradouro;
    private string $endereco_numero;
    private string $endereco_complemento;
    private string $endereco_bairro;
    private EnderecoEstado $endereco_estado;
    private string $endereco_cidade;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        $this->setarPropriedade();
        $this->validarDado();
        $this->buscarUsuario();
        $this->validarCpf();
        $this->validarCampoUnico();
        $this->validarHash();
        $this->salvarUsuario();
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
    }

    private function validarDado()
    {
        if ($this->nome->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } elseif (!$this->nome->valido()) {
            mensagemErro('Campo inválido!', 'O campo nome não é um valor válido.');
        } elseif ($this->cpf->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (!$this->cpf->valido()) {
            mensagemErro('Campo inválido!', 'O campo CPF não é um valor válido.');
        } elseif ($this->data_nascimento->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo data de nascimento é obrigatório.');
        } elseif (!$this->data_nascimento->valido()) {
            mensagemErro('Campo inválido!', 'O campo data de nascimento não é um valor válido.');
        } elseif ($this->genero->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo gênero é obrigatório.');
        } elseif (!$this->genero->valido()) {
            mensagemErro('Campo inválido!', 'O campo gênero não é um valor válido.');
        } elseif (!$this->estado_civil->vazio() && !$this->estado_civil->valido()) {
            mensagemErro('Campo inválido!', 'O campo estado civil não é um valor válido.');
        } elseif ($this->email_pessoal->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo e-mail pessoal é obrigatório.');
        } elseif (!$this->email_pessoal->valido()) {
            mensagemErro('Campo inválido!', 'O campo e-mail pessoal não é um valor válido.');
        } elseif (!$this->email_trabalho->vazio() && !$this->email_trabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo e-mail de trabalho não é um valor válido.');
        } elseif ($this->telefone_pessoal->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo telefone pessoal é obrigatório.');
        } elseif (!$this->telefone_pessoal->valido()) {
            mensagemErro('Campo inválido!', 'O campo telefont pessoal não é um valor válido.');
        } elseif (!$this->telefone_trabalho->vazio() && !$this->telefone_trabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo telefone de trabalho não é um valor válido.');
        } elseif (!$this->endereco_cep->vazio() && !$this->endereco_cep->valido()) {
            mensagemErro('Campo inválido!', 'O campo CEP do endereço não é um valor válido.');
        } elseif ($this->endereco_estado->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo estado do endereço é obrigatório.');
        } elseif (!$this->endereco_estado->valido()) {
            mensagemErro('Campo inválido!', 'O campo estado do endereço não é um valor válido.');
        } elseif (empty($this->endereco_cidade)) {
            mensagemErro('Campo obrigatório!', 'O campo cidade do endereço é obrigatório.');
        } elseif ($this->senha->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo senha é obrigatório.');
        } elseif (!$this->senha->valido()) {
            mensagemErro('Campo inválido!', 'O campo senha não é um valor válido.');
        } elseif ($this->termo->valor() != Botao::SIM) {
            mensagemErro('Campo inválido!', 'Você deve aceitar os termos para ativar seu cadastro.');
        }
    }

    private function buscarUsuario()
    {
        $usuario = $this
            ->campo(['id', 'id_admin_empresa', 'cpf', 'hash', 'hash_data', 'hash_tipo'])
            ->where(['hash', $this->hash])
            ->primeiro();
        if (!$usuario) {
            mensagemErro('Erro!', $this->erroPadrao);
        }
        $this->usuario = $usuario;
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

    private function whereEmail($id, $empresa, $email)
    {
        return [
            ['id', '!=', $id],
            ['id_admin_empresa', $empresa],
            [
                'OR',
                ['email_pessoal', $email],
                ['email_trabalho', $email]
            ]
        ];
    }

    private function salvarUsuario()
    {
        $hoje = hoje();
        $agora = agora();
        $salvar = $this
            ->dado([
                'hash'                 => '',
                'hash_data'            => '',
                'hash_tipo'            => '',
                'nome'                 => $this->nome->nome(),
                'cpf'                  => $this->cpf->numero(),
                'genero'               => $this->genero->numero(),
                'salt'                 => $this->senha->senha(),
                'data_termo'           => $hoje,
                'data_email'           => $hoje,
                'data_password'        => $agora,
                'data_ativacao'        => $agora,
                'data_dado'            => $hoje,
                'data_nascimento'      => $this->data_nascimento->date(),
                'estado_civil'         => $this->estado_civil->numero(),
                'email_pessoal'        => $this->email_pessoal->email(),
                'email_trabalho'       => $this->email_trabalho->email(),
                'telefone_celular'     => $this->telefone_pessoal->numero(),
                'telefone_fixo'        => $this->telefone_trabalho->numero(),
                'endereco_cep'         => $this->endereco_cep->numero(),
                'endereco_logradouro'  => $this->endereco_logradouro,
                'endereco_numero'      => $this->endereco_numero,
                'endereco_complemento' => $this->endereco_complemento,
                'endereco_bairro'      => $this->endereco_bairro,
                'endereco_estado'      => $this->endereco_estado->valor(),
                'endereco_cidade'      => $this->endereco_cidade,
                'mensagem'             => 1,
                'primeiro_acesso'      => 1,
                'status'               => 1,
            ])
            ->where(['id', $this->usuario->id])
            ->update();

        if (!$salvar) {
            mensagemErro('Erro!', $this->erroPadrao);
        }
    }
}
