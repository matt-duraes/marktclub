<?php

namespace App\Models\Api\UsuarioCliente\Senha;

use ORM\ORM;
use stdClass;
use Modules\Cpf;
use Helpers\OrmHelper;
use Helpers\EmailHelper;
use App\Classes\UsuarioCliente\TipoUsuario;

final class EnviarCodigoModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private stdClass $empresa;
    private stdClass $usuario;
    private string $codigo;
    private string $erroPadrao = 'Ocorreu um erro ao validar os dados enviados, por favor, tente novamente.';
    public string $id;

    public function __construct(
        string $empresa,
        private Cpf $cpf,
    ) {
        parent::__construct();
        $this->validarDado($empresa);
        $this->buscarEmpresa($empresa);
        $this->buscarUsuario();
        $this->criarCodigo();
        $this->enviarEmail();
    }

    private function validarDado($empresa)
    {
        if (empty($empresa)) {
            mensagemErro('Erro!', $this->erroPadrao);
        } elseif ($this->cpf->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (!$this->cpf->valido()) {
            mensagemErro('Campo inválido!', 'O campo CPF não é um valor válido.');
        }
    }

    private function buscarEmpresa($empresa)
    {
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarPrimeiroRegistro(
            where:['uuid', $empresa],
            campo: ['id', 'titulo', 'nome_fantasia'],
            retorno: 'object',
            erroMensagem: $this->erroPadrao
        );
    }

    private function buscarUsuario()
    {
        $usuario = $this
            ->campo(['id', 'uuid', 'nome', 'email_pessoal', 'email_trabalho', 'status'])
            ->where([
                ['cpf', $this->cpf->numero()],
                [
                    'OR',
                    ['empresa', $this->empresa->id],
                    [
                        ['empresa', 1],
                        ['tipo', (new TipoUsuario(TipoUsuario::SUPER))->numero()]
                    ]
                ]
            ])
            ->primeiro();

        if (empty($usuario)) {
            mensagemErro(
                'Usuário não encontrado!',
                'Não foi encontrado nenhum usuário pelo CPF informado, por favor, tente novamente.',
                status: 404
            );
        } elseif ($usuario->status == 3) {
            mensagemErro(
                'Erro!',
                'Sua conta está bloqueada, procure o atendimento para verificar o motivo.',
                status: 403
            );
        } elseif ($usuario->status != 1) {
            mensagemErro(
                'Erro!',
                'Sua conta não está ativa, você deve ativar sua conta para continuar.'
            );
        }
        $this->id = $usuario->uuid;
        $this->usuario = $usuario;
    }

    private function criarCodigo()
    {
        $codigo = strCodigo(tamanho: 6, minusculo: false, maiusculo: false);
        $this
            ->dado([
                'codigo_valor' => $codigo,
                'codigo_data'  => agora()
            ])
            ->where(['id', $this->usuario->id])
            ->update();
        $this->codigo = $codigo;
    }

    private function enviarEmail()
    {
        $email = !empty($this->usuario->email_pessoal) ? $this->usuario->email_pessoal : $this->usuario->email_trabalho;
        if (empty($email)) {
            mensagemErro(
                'Erro!',
                'Você não tem e-mail cadastrado para recuperar sua senha, entre em contato com o atendimento para continuar.'
            );
        }
        $email = 'andrerodrigues@andrerodrigues.com';
        $Email = new EmailHelper();
        $Email->sendGrid(
            titulo: 'Recuperar Senha',
            nome: $this->usuario->nome,
            email: $email,
            mensagem: $this->mensagem(),
            deNome: !empty($this->empresa->titulo) ? $this->empresa->titulo : $this->empresa->nome_fantasia
        );
    }

    private function mensagem()
    {
        return '
            Olá ' . $this->usuario->nome . ', para recuperar sua senha, use o código abaixo: <br>
            <strong>' . $this->codigo . '</strong><br><br>
            Caso não tenha feito esse pedido, entre em contato.<br><br>
            Esse é um e-mail automático, por favor, não responda.<br>
            Caso precise de ajuda, entre em contato pelo nossos canais de atendimento.
        ';
    }
}
