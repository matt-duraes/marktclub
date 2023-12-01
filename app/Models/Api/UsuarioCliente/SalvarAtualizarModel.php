<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;
use Modules\Cpf;
use Modules\Nome;
use Modules\Email;
use Status\StatusInterface;
use Modules\ModuleInterface;
use App\Classes\UsuarioCliente\SalvarAtualizar;

final class SalvarAtualizarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private array $usuario = [];
    private array $campoBusca = [];
    private array $dadoSalvar = [];
    public SalvarAtualizar $acao;
    public Nome $nome;
    public Email $email_pessoal;
    public Email $email_trabalho;
    public Cpf $cpf;

    /**
     * Salvar ou atualiza um usuário
     *
     * @param int  $empresa   ID da empresa
     * @param bool $salvar    Se true, vai salvar o usuário, se false, retorna para salvar
     * @param bool $atualizar Se true, vai atualizar todos os dados do usuário, se false, só atualiza os dados vazio
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
        foreach (['nome', 'email_pessoal', 'email_trabalho', 'cpf'] as $campo) {
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
        $this->usuario = $this
            ->campo(array_merge($this->campoBusca, ['id']))
            ->where([
                ['id_admin_empresa' => $this->empresa],
                ['cpf', $this->cpf->numero()]
            ])
            ->primeiro(retorno: 'array');
    }

    private function verificarAcaoTomar()
    {
        if (empty($this->usuario) && !$this->salvar) {
            $this->acao = SalvarAtualizar::CADASTRAR_USUARIO;
            return;
        } elseif (empty($this->usuario)) {
            $this->acao = SalvarAtualizar::USUARIO_NOVO;
            $this->salvarUsuario();
            return;
        }
        $this->acao = SalvarAtualizar::USUARIO_EXISTENTE;
        $this->atualizarUsuario();
    }

    private function salvarUsuario()
    {
        $salvar = $this
            ->dado($this->dadoSalvar)
            ->insert();
        if (!empty($salvar)) {
            return;
        }
        mensagemErro('Erro!', 'Ocorreu um erro ao atualizar o usuário, por favor, tente novamente.');
    }

    private function atualizarUsuario()
    {
        $dado = $this->tratarDadoParaAtualizar();
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
        $dado = $this->dadoSalvar;
        if (true === $this->atualizar) {
            return $dado;
        }

        $usuario = $this->usuario;
        foreach ($dado as $campo) {
            if (empty($usuario[$campo])) {
                continue;
            }
            unset($dado[$campo]);
        }
        return $dado;
    }
}
