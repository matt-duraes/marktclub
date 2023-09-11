<?php

namespace App\Models\Api\UsuarioCliente\Ativar;

use ORM\ORM;
use Modules\Cpf;
use Helpers\OrmHelper;
use App\Classes\UsuarioCliente\Hash;
use App\Classes\ConstrutorClube\TipoAtivacao;

final class BuscarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    public string $hash = '';
    public Cpf $cpf;
    private string $erroGeral = 'Não foi possível ativar seu usuário, procure o atendimento para verificar o motivo.';

    public function __construct(
        private TipoAtivacao $chave,
        private string $valor,
        private ?string $empresa = null
    ) {
        parent::__construct();
        $this->cpf = new Cpf(null);
        $this->validarDado();
        $this->buscarUsuario();
    }

    private function buscarUsuario()
    {
        $usuario = $this
            ->campo(['id', 'cpf', 'status'])
            ->where($this->pegarWhere())
            ->primeiro();

        $this->validarUsuario($usuario);
        $this->criarHash($usuario->id);
        $this->cpf = new Cpf($usuario->cpf);
    }

    private function validarDado()
    {
        if (!$this->chave->valido()) {
            mensagemErro('Campo inválido!', 'Você deve enviar uma chave válida.');
        }
        $indice = $this->chave->indice();
        $vazio = empty($this->valor);
        if ($indice == TipoAtivacao::CPF && $vazio) {
            mensagemErro('Campo obrigatorio!', 'Digite seu CPF para continuar.');
        } elseif ($indice == TipoAtivacao::CPF && !validarCpf($this->valor)) {
            mensagemErro('Campo inválido!', 'Digite um CPF válido para continuar.');
        } elseif ($indice == TipoAtivacao::MATRICULA && $vazio) {
            mensagemErro('Campo obrigatorio!', 'Digite sua matrícula para continuar.');
        } elseif ($indice == TipoAtivacao::SIAPE && $vazio) {
            mensagemErro('Campo obrigatorio!', 'Digite seu SIAPE para continuar.');
        } elseif ($indice == TipoAtivacao::EMAIL && $vazio) {
            mensagemErro('Campo obrigatorio!', 'Digite seu e-mail para continuar.');
        } elseif ($indice == TipoAtivacao::EMAIL && !validarEmail($this->valor)) {
            mensagemErro('Campo inválido!', 'Digite um e-mail válido para continuar.');
        }
    }

    private function criarHash($id)
    {
        $hash = uuid();
        try {
            $this
            ->dado([
                'hash_valor' => $hash,
                'hash_data'  => agora(),
                'hash_tipo'  => Hash::ATIVAR
            ])
            ->where(['id', $id])
            ->update();
        } catch (\Throwable) {
            mensagemErro('Erro!', $this->erroGeral, codigo: 5000);
        }
        $this->hash = $hash;
    }

    private function validarUsuario($usuario): void
    {
        if (is_array($usuario) && empty($usuario)) {
            mensagemErro(
                'Usuário não encontrado!',
                'Não foi possível achar seu usuário pelos dados informados, por favor, verifique os dados informados e tente novamente. Caso os dados estejam corretos, entre em contato com o atendimento.',
                codigo: 4040
            );
        } elseif (!is_object($usuario) || !object_key_exists('id', $usuario) || empty($usuario->id)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao achar seus dados, por favor, tente novamente.', codigo: 5000);
        } elseif ($usuario->status == 1) {
            mensagemErro(
                'Usuário ativo!',
                'Seu usuário já está ativo no sistema, faça seu login para continuar, caso não lembre da sua senha, recupere sua senha ou entre em contato com o atendimento.',
                codigo: 1000
            );
        } elseif ($usuario->status == 3) {
            mensagemErro('Procure atendimento!', $this->erroGeral, codigo: 4030);
        } elseif ($usuario->status == 5) {
            mensagemErro('Procure atendimento!', 'Não é possível ativar um usuário que foi indicado.', codigo: 4032);
        } elseif ($usuario->status != 2) {
            mensagemErro('Procure atendimento!', $this->erroGeral, codigo: 4033);
        }
    }

    private function pegarWhere(): array
    {
        $indice = $this->chave->indice();
        $valor = $this->valor;
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($this->empresa);
        if ($indice == TipoAtivacao::EMAIL) {
            return [
                [
                    'OR',
                    ['email_pessoal', $valor],
                    ['email_trabalho', $valor]
                ],
                ['id_admin_empresa', $empresa]
            ];
        }
        if (in_array($indice, [TipoAtivacao::CPF, TipoAtivacao::SIAPE])) {
            $valor = soNumero($valor);
        }
        return [
            [$indice, $valor],
            ['id_admin_empresa', $empresa]
        ];
    }
}
