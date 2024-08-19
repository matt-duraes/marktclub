<?php

namespace App\Models\Api\UsuarioCliente\Ativar;

use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use App\Models\Api\UsuarioCliente\Ativar\Trait\AtivarTrait;
use Http\Request;
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
use ORM\ORM;
use stdClass;

final class AtivarModel extends ORM
{
    use AtivarTrait;

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
    private string $grupo;
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
    private string|int|null $trabalho_cargo;
    private TrabalhoEmpresa $trabalho_empresa;

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
                'grupo'                => $this->grupo,
                'email_pessoal'        => $this->email_pessoal->email(),
                'email_trabalho'       => $this->email_trabalho->email(),
                'telefone_celular'     => $this->telefone_pessoal->numero(),
                'telefone_fixo'        => $this->telefone_trabalho->numero(),
                'trabalho_cargo'       => $this->trabalho_cargo,
                'trabalho_orgao'       => $this->trabalho_empresa->numero(),
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
}
