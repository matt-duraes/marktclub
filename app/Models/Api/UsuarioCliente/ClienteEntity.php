<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\Entity;
use Http\Request;
use Modules\Botao;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\UsuarioGrupo\GrupoEntity;
use App\Models\Api\Painel\ConfiguracaoEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioPagamento\PagamentoModel;
use App\Models\Api\UsuarioCliente\Trait\CampoUnicoTrait;
use App\Models\Api\UsuarioCliente\Trait\PropriedadeEntityTrait;

final class ClienteEntity extends Entity
{
    use ValidarEmpresaTrait;
    use CampoUnicoTrait;
    use PropriedadeEntityTrait;

    protected string $_tabela = TABELA_USUARIO_NOVO;

    public function __construct(
        private ?Request $request = null,
        private bool $validarToken = true
    ) {
        parent::__construct();
        if (!$validarToken) {
            return;
        }
        $this->validarEmpresa();
        $this->pegarCampoObrigatorio();
    }
    private function pegarCampoObrigatorio()
    {
        try {
            $Config = new ConfiguracaoEntity();
            $this->campoObrigatorio = $Config->campo_obrigatorio['usuario_cliente'] ?? [];
        } catch (\Throwable) {
            $this->campoObrigatorio = ["cpf", "email", "status"];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS DE SALVAR
    |--------------------------------------------------------------------------
    */
    protected function regraSalvar()
    {
        $this->cpfExiste();
        $this->emailTrabalhoExiste();
        $this->emailPessoalExiste();
        $this->matriculaExiste();
        $this->siapeExiste();
        $this->grupoValido();
        $this->tipo = new TipoUsuario(TipoUsuario::TITULAR);
    }

    private function grupoValido()
    {
        $Grupo = new GrupoEntity();
        if (
            !empty($this->request->grupo) &&
            !$Grupo->existe([
                ['indice', $this->grupo],
                ['id_admin_empresa', $this->idEmpresa]
            ])
        ) {
            mensagemErro('Campo inválido!', 'O grupo informado não é um valor válido.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA O INSERT
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        $this->cod = uuid();
        $this->mensagem = new Botao('sim');

        $this->validarCamposObrigatorioNoInsert();

        if (!$this->request->existe('status') || empty($this->request->status)) {
            $this->status = new Status('inativo');
        }
    }
    private function validarCamposObrigatorioNoInsert()
    {
        $request = $this->request;
        $campoObrigatorio = $this->campoObrigatorio;
        $emailPessoal = $request->existe('email_pessoal') ? $this->email_pessoal->email() : '';
        $emailTrabalho = $request->existe('email_trabalho') ? $this->email_trabalho->email() : '';

        if (
            in_array('nome', $campoObrigatorio) &&
            (!$request->existe('nome') || $this->nome->vazio())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } else if (
            in_array('cpf', $campoObrigatorio) &&
            (!$request->existe('cpf') || $this->cpf->vazio())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } else if (
            in_array('email', $campoObrigatorio) && empty($emailPessoal) && empty($emailTrabalho)
        ) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail para salvar.');
        } else if (
            in_array('status', $campoObrigatorio) &&
            (!$request->existe('status') || $this->status->vazio())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo status é obrigatório.');
        } else if (
            in_array('matricula', $campoObrigatorio) &&
            (!$request->existe('matricula') || empty($this->matricula))
        ) {
            mensagemErro('Campo obrigatório!', 'O campo matrícula é obrigatório.');
        } else if (
            in_array('siape', $campoObrigatorio) &&
            (!$request->existe('siape') || empty($this->siape))
        ) {
            mensagemErro('Campo obrigatório!', 'O campo siape é obrigatório.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA O UPDATE
    |--------------------------------------------------------------------------
    */
    protected function regraUpdate()
    {
        $cpfAtual = $this->prop('documento');
        if (!empty($cpfAtual) && validarCpf($cpfAtual) && $cpfAtual != $this->cpf->numero()) {
            mensagemErro('Erro!', 'Você não pode mudar o CPF desse usuário.');
        }
        $this->validarCamposObrigatorioNoUpdate();
    }
    private function validarCamposObrigatorioNoUpdate()
    {
        $campoObrigatorio = $this->campoObrigatorio;
        $request = $this->request;
        $emailExiste = $request->existe('email_pessoal') || $request->existe('email_trabalho');

        if (
            in_array('nome', $campoObrigatorio) && $request->existe('nome') && $this->nome->vazio()
        ) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } else if (
            in_array('cpf', $campoObrigatorio) && $request->existe('cpf') && $this->cpf->vazio()
        ) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } else if (
            in_array('email', $campoObrigatorio) &&
            $emailExiste &&
            $this->email_pessoal->vazio() &&
            $this->email_trabalho->vazio()
        ) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail para salvar.');
        } else if (
            in_array('status', $campoObrigatorio) && $request->existe('status') && !$this->status->valido()
        ) {
            mensagemErro('Campo obrigatório!', 'O campo status é obrigatório.');
        } else if (
            in_array('matricula', $campoObrigatorio) && $request->existe('matricula') && empty($this->matricula)
        ) {
            mensagemErro('Campo obrigatório!', 'O campo matrícula é obrigatório.');
        } else if (
            in_array('siape', $campoObrigatorio) && $request->existe('siape') && empty($this->siape)
        ) {
            mensagemErro('Campo obrigatório!', 'O campo siape é obrigatório.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA BUSCAR
    |--------------------------------------------------------------------------
    */
    protected function regraPosBuscar()
    {
        $this->contratoSiape = '';
        if (!$this->trabalho_empresa->vazio() && !empty($this->siape) && $this->idEmpresa == 19) {
            $this->contratoSiape = $this->trabalho_empresa->numero() . $this->siape . '341201';
        }
        $this->imagem = imagemUsuario();

        if ($this->validarToken) {
            $Pagamento = new PagamentoModel();
            $this->pagamento = $Pagamento->buscarPagamento($this->get('id'));
        }
    }

    public function getId()
    {
        return $this->prop('id');
    }

    public function getCpf()
    {
        return $this->prop('documento');
    }
}
