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
    public array $email = [];

    public function __construct(
        string $empresa,
        private Cpf $cpf = new Cpf(null),
        private ?string $usuarioId = null
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
        } elseif ($this->cpf->vazio() && is_null($this->usuarioId)) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (!$this->cpf->valido() && is_null($this->usuarioId)) {
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
            ->where($this->pegarWhere())
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

    private function pegarWhere()
    {
        $where = is_null($this->usuarioId) ? [['cpf', $this->cpf->numero()]] : [['uuid', $this->usuarioId]];
        $where[] = [
            [
                'OR',
                ['empresa', $this->empresa->id],
                [
                    ['empresa', 1],
                    ['tipo', (new TipoUsuario(TipoUsuario::SUPER))->numero()]
                ]
            ]
        ];
        return $where;
    }

    private function criarCodigo()
    {
        $codigo = eLocalhost() ? 123456 : rand(100000, 999999);
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
        $this->email[] = $email;
        $copia = [];
        if(
            !empty($this->usuario->email_pessoal) &&
            !empty($this->usuario->email_trabalho) &&
            $this->usuario->email_pessoal != $this->usuario->email_trabalho
        ) {
            $this->email[] = $this->usuario->email_trabalho;
            $copia = [$this->usuario->email_trabalho];
        }

        $Email = new EmailHelper();
        $Email->sendGrid(
            titulo: 'Recuperar Senha',
            nome: $this->usuario->nome,
            email: $email,
            copia: $copia,
            mensagem: $this->mensagem(),
            deNome: !empty($this->empresa->titulo) ? $this->empresa->titulo : $this->empresa->nome_fantasia
        );
        $this->mascararEmail();
    }
    private function mascararEmail()
    {
        $lista = $this->email;
        $this->email = [];
        foreach($lista as $email) {
            $email = explode('@', $email);
            $dominio = explode('.', $email[1])[0];
            $final = explode('.', $email[1]);
            array_shift($final);
            $final = implode('.', $final);

            $quantidadeEmail = intdiv(mb_strlen($email[0]), 2);
            $quantidadeEmail = $quantidadeEmail > 5 ? 5 : $quantidadeEmail;
            $quantidadeDominio = intdiv(mb_strlen($dominio), 2);
            $quantidadeDominio = $quantidadeDominio > 5 ? 5 : $quantidadeDominio;

            if(!in_array($dominio, ['gmail', 'uol', 'bol', 'hotmail', 'outlook', 'yahoo', 'protonmail'])) {
                $dominio = mb_substr($dominio, 0, $quantidadeDominio, 'UTF-8') . '***';
            }
            $this->email[] = mb_substr($email[0], 0, $quantidadeEmail, 'UTF-8')
                . '***@'
                . $dominio
                . '.' . $final;
        }
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
