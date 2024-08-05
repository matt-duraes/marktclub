<?php

namespace App\Models\Oauth\Usuario;

use ORM\ORM;
use Erro\Excecao;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use App\Classes\UsuarioCliente\Hash;

final class SalvarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private int $idUsuario = 0;
    private string $emailPessoalUsuario;
    private string $emailTrabalhoUsuario;
    private string $linkClube;
    private string $hash;
    public bool $existe = false;

    /**
     * @param int    $empresa
     * @param string $nome
     * @param int    $cpf
     * @param string $email
     * @param string $grupo
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly int $empresa,
        private readonly string $nome,
        private readonly int $cpf,
        private readonly string $email_pessoal = '',
        private readonly string $email_trabalho = '',
        private readonly string $grupo = '',
        private readonly string $dataNascimento = '',
        private readonly string $crmNumero = '',
        private readonly string $crmEstado = '',
        public string $cadastro = ''
    ) {
        parent::__construct();

        $this->hash = uuid();
        $this->pegarIdUsuario();
        $this->pegarLinkClube();
        if (!empty($this->idUsuario)) {
            $this->atualizarUsuario();
            return;
        } elseif ($cadastro == 'sim') {
            return;
        }
        $this->salvarUsuario();
    }

    /**
     */
    private function pegarLinkClube(): void
    {
        $this->linkClube = (new OrmHelper(TABELA_CONSTRUTOR_CLUBE, livre: true))->pegarCampoPor(
            campo: 'link_clube',
            where: [
                ['id_admin_empresa', $this->empresa],
                ['status', (new Status(Status::ATIVO))->numero()]
            ]
        );
    }

    /**
     * @throws Excecao
     */
    private function pegarIdUsuario(): void
    {
        $usuario = $this
            ->campo(['id', 'email_pessoal', 'email_trabalho'])
            ->where([
                ['documento', $this->cpf],
                ['empresa', $this->empresa]
            ])
            ->primeiro();
        if (!is_object($usuario) || !object_key_exists('id', $usuario)) {
            return;
        }
        $this->idUsuario = $usuario->id;
        $this->emailPessoalUsuario = $usuario->email_pessoal ?? '';
        $this->emailTrabalhoUsuario = $usuario->email_trabalho ?? '';
    }

    /**
     * @throws Excecao
     */
    private function atualizarUsuario(): void
    {
        $agora = agora();
        $hoje = hoje();
        $dado = [
            'nome'             => $this->nome,
            'hash'             => $this->hash,
            'hash_data'        => agora(),
            'hash_tipo'        => Hash::LOGIN,
            'data_atualizacao' => $agora,
            'status'           => 1
        ];

        $emailPessoal = strCaixaBaixa($this->email_pessoal);
        if ($emailPessoal != strCaixaBaixa($this->emailPessoalUsuario)) {
            $dado['email_pessoal'] = $emailPessoal;
            $dado['data_email'] = $hoje;
        }
        $emailTrabalho = strCaixaBaixa($this->email_trabalho);
        if ($emailTrabalho != strCaixaBaixa($this->emailTrabalhoUsuario)) {
            $dado['email_trabalho'] = $emailTrabalho;
            $dado['data_email'] = $hoje;
        }
        if (!empty($this->grupo)) {
            $dado['grupo'] = $this->grupo;
        }
        if (!empty($this->crmNumero)) {
            $dado['crm_numero'] = $this->crmNumero;
        }
        if (!empty($this->crmEstado)) {
            $dado['crm_estado'] = $this->crmEstado;
        }
        if (!empty($this->dataNascimento)) {
            $dado['data_nascimento'] = $this->dataNascimento;
        }

        $dado = $this
            ->dado($dado)
            ->where(['id', $this->idUsuario])
            ->update();

        if (!$dado) {
            mensagemStatus(401, localhost: 'Não foi possível atualizar usuário.');
        }
        $this->existe = true;
    }

    /**
     * @throws Excecao
     */
    private function salvarUsuario(): void
    {
        $agora = agora();
        $hoje = hoje();
        $dado = $this->dado([
            'cod'               => uuid(),
            'tipo'              => 1,
            'empresa'           => $this->empresa,
            'nome'              => $this->nome,
            'documento'         => (int)soNumero($this->cpf),
            'email_pessoal'     => strCaixaBaixa($this->email_pessoal),
            'email_trabalho'    => strCaixaBaixa($this->email_trabalho),
            'grupo'             => !empty($this->grupo) ? strCaixaBaixa($this->grupo) : '',
            'crm_numero'        => !empty($this->crmNumero) ? $this->crmNumero : '',
            'crm_estado'        => !empty($this->crmEstado) ? $this->crmEstado : '',
            'data_nascimento'   => !empty($this->dataNascimento) ? $this->dataNascimento : '',
            'data_criacao'      => $agora,
            'data_atualizacao'  => $agora,
            'data_email'        => $hoje,
            'data_ativacao'     => $agora,
            'data_dado'         => $hoje,
            'hash'              => $this->hash,
            'hash_tipo'         => Hash::LOGIN,
            'hash_data'         => $agora,
            'status'            => 1
        ])->insert();
        if (!$dado) {
            mensagemStatus(401, localhost: 'Não foi possível salvar usuário.');
        }
        $this->existe = true;
    }

    /**
     * Pega o link para fazer login
     *
     * @return string
     */
    public function pegarLink(): string
    {
        return 'https://' . str_replace(['https://', 'http://'], '', $this->linkClube) . '/login/api/' . $this->hash;
    }
}
