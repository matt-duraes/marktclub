<?php

namespace App\Models\Oauth\Usuario;

use App\Classes\ComercialEmpresa\Helper;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use Erro\Excecao;
use ORM\ORM;

final class SalvarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private int $idUsuario = 0;
    private string $emailUsuario;
    private string $linkClube;
    private string $hash;

    /**
     * @param  int     $empresa
     * @param  string  $nome
     * @param  int     $cpf
     * @param  string  $email
     * @param  string  $grupo
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
     * @return void
     */
    private function pegarLinkClube(): void
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['empresa', $this->empresa],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);
        $this->linkClube = $Construtor->link_clube;
    }

    /**
     * @return void
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
     * @return void
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

    private function salvarUsuario()
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
            'nome'             => $this->nome,
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

    private function pegarLinkClube()
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['empresa', $this->empresa],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);
        $this->linkClube = $Construtor->link_clube;
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
