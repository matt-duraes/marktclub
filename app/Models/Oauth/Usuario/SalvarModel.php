<?php

namespace App\Models\Oauth\Usuario;

use ORM\ORM;
use Erro\Excecao;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;

final class SalvarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private int $idUsuario = 0;
    private string $emailUsuario;
    private string $linkClube;
    private string $hash;

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
        private readonly string $email,
        private readonly string $grupo
    ) {
        parent::__construct();

        $this->hash = uuid();
        $this->pegarLinkClube();
        $this->pegarIdUsuario();
        if (!empty($this->idUsuario)) {
            $this->atualizarUsuario();
            return;
        }
        $this->salvarUsuario();
    }

    /**
     */
    private function pegarLinkClube(): void
    {
        $this->linkClube = (new OrmHelper(TABELA_CONSTRUTOR_CLUBE))->pegarCampoPor(
            campo: 'link_clube',
            where: [
                ['empresa', $this->empresa],
                ['status', Status::ATIVO]
            ]
        );
    }

    /**
     * @throws Excecao
     */
    private function pegarIdUsuario(): void
    {
        $usuario = $this
            ->campo(['id', 'email_pessoal'])
            ->where([
                ['documento', $this->cpf],
                ['empresa', $this->empresa]
            ])
            ->primeiro();
        if (!is_object($usuario) || !object_key_exists('id', $usuario)) {
            return;
        }
        $this->idUsuario = $usuario->id;
        $this->emailUsuario = $usuario->email_pessoal ?? '';
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
            'grupo'            => $this->grupo,
            'hash'             => $this->hash,
            'hash_data'        => agora(),
            'data_atualizacao' => $agora,
            'status'           => 1
        ];

        $email = strCaixaBaixa($this->email);
        if ($email != strCaixaBaixa($this->emailUsuario)) {
            $dado['email_pessoal'] = $email;
            $dado['data_email'] = $hoje;
        }

        $dado = $this->dado($dado)->where(['id', $this->idUsuario])->update();
        if (!$dado) {
            mensagemStatus(401, localhost: 'Não foi possível atualizar usuário.');
        }
    }

    /**
     * @throws Excecao
     */
    private function salvarUsuario(): void
    {
        $agora = agora();
        $hoje = hoje();
        $dado = $this->dado([
            'cod'              => uuid(),
            'tipo'             => 1,
            'empresa'          => $this->empresa,
            'nome'             => $this->nome,
            'documento'        => (int)soNumero($this->cpf),
            'email_pessoal'    => strCaixaBaixa($this->email),
            'grupo'            => strCaixaBaixa($this->grupo),
            'data_criacao'     => $agora,
            'data_atualizacao' => $agora,
            'data_email'       => $hoje,
            'data_ativacao'    => $agora,
            'data_dado'        => $hoje,
            'hash'             => $this->hash,
            'hash_data'        => $agora,
            'status'           => 1
        ])->insert();
        if (!$dado) {
            mensagemStatus(401, localhost: 'Não foi possível salvar usuário.');
        }
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
