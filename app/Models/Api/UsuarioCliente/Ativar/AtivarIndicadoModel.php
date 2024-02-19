<?php

namespace App\Models\Api\UsuarioCliente\Ativar;

use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\UsuarioIndicacao\Status as IndicacaoStatus;
use App\Models\Api\UsuarioCliente\Ativar\Trait\AtivarTrait;
use Helpers\OrmHelper;
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

final class AtivarIndicadoModel extends ORM
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
    private TipoUsuario $tipo_usuario;
    private string $empresa;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        $this->setarPropriedade();
        $this->setarPropriedadeIndicacao();
        $this->validarUsuarioRepetido();
        $this->validarDado();
        $this->salvarUsuario();
    }

    private function validarUsuarioRepetido()
    {
        $usuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))
            ->pegarPrimeiroRegistro([
                [
                    'OR',
                    ['email_pessoal', $this->email_pessoal->email()],
                    ['email_trabalho', $this->email_trabalho->email()],
                    ['documento', $this->cpf->numero()]
                ],
                ['empresa', $this->empresa]
            ], ['documento', 'email_pessoal', 'email_trabalho']);

        if ($usuario && $usuario['documento'] == $this->cpf->numero()) {
            return mensagemErro('Erro!', 'Já existe um usuário com esse CPF.');
        }

        if (
            $usuario &&
            $usuario['email_pessoal'] == $this->email_pessoal->email() &&
            $usuario['email_trabalho'] == $this->email_trabalho->email()
        ) {
            return mensagemErro('Erro!', 'Já existe um usuário com esse e-mail.');
        }
    }

    private function setarPropriedadeIndicacao()
    {
        $this->tipo_usuario = new TipoUsuario($this->request->tipo_usuario);
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($this->request->empresa);
    }

    private function salvarUsuario()
    {
        $hoje = hoje();
        $agora = agora();
        $salvar = $this
            ->dado([
                'tipo'                 => $this->tipo_usuario->numero(),
                'empresa'              => $this->empresa,
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
                'status'               => (new Status(Status::INDICACAO))->numero(),
            ])
            ->insert();

        (new OrmHelper(TABELA_USUARIO_INDICACAO))
            ->dado([
                'vinculo' => $salvar['id'],
                'status'  => (new IndicacaoStatus(IndicacaoStatus::ATIVADO))->numero()
            ])
            ->where(['hash', $this->hash])
            ->update();

        if (!$salvar) {
            mensagemErro('Erro!', $this->erroPadrao);
        }
    }
}
