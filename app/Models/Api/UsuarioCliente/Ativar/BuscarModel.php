<?php

namespace App\Models\Api\UsuarioCliente\Ativar;

use App\Classes\ConstrutorClube\TipoAtivacao;
use App\Classes\UsuarioCliente\Hash;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Cpf;
use ORM\ORM;
use Throwable;

final class BuscarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private string $erroGeral = 'Não foi possível ativar seu usuário, procure o atendimento para verificar o motivo.';
    private Cpf $cpf;
    private string $hash;

    /**
     * @throws Excecao
     */
    public function __construct(
        private readonly string $valor,
        private readonly ?string $empresa = null,
        private readonly TipoAtivacao $tipoAtivacao = new TipoAtivacao(),
        private readonly TipoUsuario $tipoUsuario = new TipoUsuario(),
    ) {
        parent::__construct();
        $this->validarDados();
        $this->buscarUsuario();
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (!$this->tipoUsuario->vazio()) {
            if (!$this->tipoUsuario->valido()) {
                mensagemErro('Usuário!', 'Não foi possível identificar seu usuário.');
            }

            if ($this->tipoUsuario->indice() === TipoUsuario::DEPENDENTE) {
                if (empty($this->valor)) {
                    mensagemErro('Campo obrigatorio!', 'Digite seu CPF para continuar.');
                } elseif (!validarCpf($this->valor)) {
                    mensagemErro('Campo inválido!', 'Digite um CPF válido para continuar.');
                }

                return;
            }
        }

        if (!$this->tipoAtivacao->valido()) {
            mensagemErro('Campo inválido!', 'Você deve enviar uma chave válida.');
        }

        if (($this->tipoAtivacao->indice() === TipoAtivacao::CPF) && empty($this->valor)) {
            mensagemErro('Campo obrigatorio!', 'Digite seu CPF para continuar.');
        } elseif (($this->tipoAtivacao->indice() === TipoAtivacao::CPF) && !validarCpf($this->valor)) {
            mensagemErro('Campo inválido!', 'Digite um CPF válido para continuar.');
        } elseif (($this->tipoAtivacao->indice() === TipoAtivacao::MATRICULA) && empty($this->valor)) {
            mensagemErro('Campo obrigatorio!', 'Digite sua matrícula para continuar.');
        } elseif (($this->tipoAtivacao->indice() === TipoAtivacao::SIAPE) && empty($this->valor)) {
            mensagemErro('Campo obrigatorio!', 'Digite seu SIAPE para continuar.');
        } elseif (($this->tipoAtivacao->indice() === TipoAtivacao::EMAIL) && empty($this->valor)) {
            mensagemErro('Campo obrigatorio!', 'Digite seu e-mail para continuar.');
        } elseif (($this->tipoAtivacao->indice() === TipoAtivacao::EMAIL) && !validarEmail($this->valor)) {
            mensagemErro('Campo inválido!', 'Digite um e-mail válido para continuar.');
        }
    }

    /**
     * @throws Excecao
     */
    private function buscarUsuario(): void
    {
        $usuario = $this
            ->campo([
                'id', 'cpf', 'status'
            ])
            ->where($this->pegarWhere())
            ->primeiro();

        $this->validarUsuario($usuario);
        $this->criarHash($usuario->id);
        $this->setarCpf($usuario->cpf);
    }

    /**
     * @return array[]
     */
    private function pegarWhere(): array
    {
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarIdPeloUuid($this->empresa);

        if (
            ($this->tipoUsuario !== null)
            && ($this->tipoUsuario->indice() === TipoUsuario::DEPENDENTE)
        ) {
            return [
                ['cpf', soNumero($this->valor)],
                ['id_admin_empresa', $empresa]
            ];
        }

        if ($this->tipoAtivacao->indice() === TipoAtivacao::EMAIL) {
            return [
                [
                    'OR',
                    ['email_pessoal', $this->valor],
                    ['email_trabalho', $this->valor]
                ],
                ['id_admin_empresa', $empresa]
            ];
        }
        if (in_array($this->tipoAtivacao->indice(), [TipoAtivacao::CPF, TipoAtivacao::SIAPE], true)) {
            return [
                ['id_admin_empresa', $empresa],
                [$this->tipoAtivacao->indice(), soNumero($this->valor)]
            ];
        }
        return [
            ['id_admin_empresa', $empresa],
            [$this->tipoAtivacao->indice(), $this->valor]
        ];
    }

    /**
     * @param array|object $usuario
     *
     * @throws Excecao
     */
    private function validarUsuario(array|object $usuario): void
    {
        if (is_array($usuario) || empty($usuario)) {
            mensagemErro(
                'Usuário não encontrado!',
                'Não foi possível achar seu usuário pelos dados informados. Por favor, verifique os dados informados e tente novamente. Caso os dados estejam corretos, entre em contato com o atendimento.',
                codigo: 4040
            );
        }

        $status = new Status($usuario->status);
        if (!object_key_exists('id', $usuario) || empty($usuario->id)) {
            mensagemErro(
                'Erro!',
                'Ocorreu um erro ao achar seus dados, por favor, tente novamente.',
                codigo: 5000
            );
        } elseif ($status->indice() === Status::ATIVO) {
            mensagemErro(
                'Usuário ativo!',
                'Seu usuário já está ativo no sistema, faça seu login para continuar, caso não lembre da sua senha, recupere sua senha ou entre em contato com o atendimento.',
                codigo: 1000
            );
        } elseif ($status->indice() === Status::BLOQUEADO) {
            mensagemErro('Procure atendimento!', $this->erroGeral, codigo: 4030);
        } elseif ($status->indice() === Status::INDICACAO) {
            mensagemErro(
                'Procure atendimento!',
                'Não é possível ativar um usuário que foi indicado.',
                codigo: 4032
            );
        } elseif ($status->indice() !== Status::INATIVO) {
            mensagemErro('Procure atendimento!', $this->erroGeral, codigo: 4033);
        }
    }

    /**
     * @param string|null $id
     *
     * @throws Excecao
     */
    private function criarHash(?string $id): void
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
        } catch (Throwable) {
            mensagemErro('Erro!', $this->erroGeral, codigo: 5000);
        }
        $this->hash = $hash;
    }

    /**
     * @param string|null $cpf
     */
    private function setarCpf(?string $cpf): void
    {
        $this->cpf = new Cpf($cpf);
    }

    /**
     * @return Cpf
     */
    public function pegarCpf(): Cpf
    {
        return $this->cpf;
    }

    /**
     * @return string
     */
    public function pegarHash(): string
    {
        return $this->hash;
    }
}
