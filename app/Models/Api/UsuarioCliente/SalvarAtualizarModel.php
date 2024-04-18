<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;
use Modules\Cpf;
use Modules\Nome;
use Modules\Email;
use Status\StatusInterface;
use Modules\ModuleInterface;
use App\Classes\UsuarioCliente\Status;

final class SalvarAtualizarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private array $usuario = [];
    private array $campoBusca = [];
    private array $dadoSalvar = [];
    public string $acao;
    public Nome $nome;
    public Email $email_pessoal;
    public Email $email_trabalho;
    public Cpf $cpf;
    public Status $status;

    public const CADASTRAR_USUARIO = 'cadastrar-usuario';
    public const USUARIO_NOVO = 'usuario-novo';
    public const USUARIO_EXISTENTE = 'usuario-existente';

    /**
     * Salvar ou atualiza um usuário
     *
     * @param int   $empresa   ID da empresa
     * @param bool  $salvar    Se true, vai salvar o usuário, se false, retorna para salvar
     * @param bool  $atualizar Se true, vai atualizar todos os dados do usuário, se false, só atualiza os dados vazio
     * @param array $campo     Campos para atualizar/salvar
     */
    public function __construct(
        private int $empresa,
        private bool $salvar = true,
        private bool $atualizar = false
    ) {
        parent::__construct();
    }

    public function buscar()
    {
        $this->montarDadoCampoSalvar();
        $this->buscarUsuarioNaBase();
        $this->verificarAcaoTomar();
    }

    private function montarDadoCampoSalvar()
    {
        foreach (['nome', 'email_pessoal', 'email_trabalho', 'cpf', 'status'] as $campo) {
            if (!$this->propriedadeExiste($campo)) {
                continue;
            }
            $dado = $this->$campo;
            if ($dado instanceof ModuleInterface && $dado->valido()) {
                $this->campoBusca[] = $campo;
                $this->dadoSalvar[$campo] = $dado->banco();
            } elseif ($dado instanceof StatusInterface && $dado->valido()) {
                $this->campoBusca[] = $campo;
                $this->dadoSalvar[$campo] = $dado->numero();
            } elseif (!($dado instanceof ModuleInterface) && (!$dado instanceof StatusInterface) && !vazio($dado)) {
                $this->campoBusca[] = $campo;
                $this->dadoSalvar[$campo] = $dado;
            }
        }
    }

    private function buscarUsuarioNaBase()
    {
        $campo = $this->campoBusca;
        if (!array_key_exists('id', $campo)) {
            $campo[] = 'id';
        }
        if (!array_key_exists('status', $campo)) {
            $campo[] = 'status';
        }
        $this->usuario = $this
            ->campo($campo)
            ->where([
                ['id_admin_empresa', $this->empresa],
                ['cpf', $this->cpf->numero()]
            ])
            ->primeiro(retorno: 'array');
    }

    private function verificarAcaoTomar()
    {
        if (empty($this->usuario) && !$this->salvar) {
            $this->acao = self::CADASTRAR_USUARIO;
            return;
        } elseif (empty($this->usuario)) {
            $this->acao = self::USUARIO_NOVO;
            $this->salvarUsuario();
            return;
        }
        $this->acao = self::USUARIO_EXISTENTE;
        $this->atualizarUsuario();
    }

    private function salvarUsuario()
    {
        $dado = $this->dadoSalvar;
        if (!array_key_exists('uuid', $dado)) {
            $dado['uuid'] = uuid();
        }
        if (!array_key_exists('data_criacao', $dado)) {
            $dado['data_criacao'] = agora();
        }
        if (!array_key_exists('tipo', $dado)) {
            $dado['tipo'] = 1;
        }
        if (!array_key_exists('status', $dado) || !in_array($dado['status'], [1, 2])) {
            $dado['status'] = 2;
        }
        $dado['id_admin_empresa'] = $this->empresa;
        $salvar = $this
            ->dado($dado)
            ->insert();
        if (!empty($salvar)) {
            return;
        }
        mensagemErro('Erro!', 'Ocorreu um erro ao atualizar o usuário, por favor, tente novamente.');
    }

    private function atualizarUsuario()
    {
        $dado = $this->tratarDadoParaAtualizar();
        if (empty($dado)) {
            return;
        }

        if (!array_key_exists('data_atualizacao', $dado)) {
            $dado['data_atualizacao'] = agora();
        }

        $salvar = $this
            ->dado($dado)
            ->where(['id', $this->usuario['id']])
            ->update();
        if (!empty($salvar)) {
            return;
        }

        mensagemErro('Erro!', 'Ocorreu um erro ao atualizar o usuário, por favor, tente novamente.');
    }

    private function tratarDadoParaAtualizar()
    {
        $usuario = $this->usuario;
        $dado = $this->dadoSalvar;
        if (!array_key_exists('status', $usuario) || !in_array($usuario['status'], [1, 2])) {
            $dado['status'] = 2;
        } elseif (array_key_exists('status', $usuario) && in_array($usuario['status'], [1, 2])) {
            unset($dado['status']);
        }
        if (true === $this->atualizar) {
            return $dado;
        }
        foreach ($dado as $campo) {
            if (empty($usuario[$campo])) {
                continue;
            }
            unset($dado[$campo]);
        }
        return $dado;
    }
}
