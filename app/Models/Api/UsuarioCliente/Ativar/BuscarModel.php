<?php

namespace App\Models\Api\UsuarioCliente\Ativar;

use ORM\ORM;
use stdClass;
use Throwable;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Botao;
use Helpers\OrmHelper;
use App\Classes\UsuarioCliente\Hash;
use App\Helpers\Ciesc\UsuarioHelper;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\ConstrutorClube\TipoAtivacao;
use App\Helpers\Cvs\AtivarHelper as CvsHelper;
use App\Classes\Usuario\Ativar\Ciesc\LocalTrabalho;

final class BuscarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private string $erroGeral = 'Não foi possível ativar seu usuário, procure o atendimento para verificar o motivo.';
    private Cpf $cpf;
    private string $hash;
    private stdClass|array $usuario;
    public string $nomeCompleto = '';
    public string $genero = '';
    public string $emailPessoal = '';
    public string $enderecoCidade = '';
    public string $enderecoEstado = '';

    /**
     * @throws Excecao
     */
    public function __construct(
        private readonly string $valor,
        private readonly ?string $empresa = null,
        private readonly TipoAtivacao $tipoAtivacao = new TipoAtivacao(),
        private readonly TipoUsuario $tipoUsuario = new TipoUsuario(),
        private readonly LocalTrabalho $localTrabalho = new LocalTrabalho(),
        private readonly Botao $Termo = new Botao()
    ) {
        parent::__construct(leitura: false);
        $this->validarDados();
        if ($this->tipoAtivacao->indice() === TipoAtivacao::CIESC && $tipoUsuario->indice() == TipoUsuario::TITULAR) {
            if ($this->Termo->valor() != Botao::SIM) {
                mensagemErro('Campo obrigatório!', 'Você deve aceitar os termo de uso para continuar.');
            }
            $this->buscarUsuarioCiesc($valor, $localTrabalho);
            return;
        }
        $this->buscarUsuario();
    }

    private function buscarUsuarioCiesc($valor, $localTrabalho)
    {
        $usuario = new UsuarioHelper(
            Cpf: new Cpf($valor),
            LocalTrabalho: $localTrabalho
        );
        if (!$usuario->existe) {
            mensagemErro(
                'Dados não encontrado!',
                'Não foi encontrado nenhum dado pelo seu CPF na empresa ' . $localTrabalho->indice() . '.'
            );
        }
        $this->nomeCompleto = $usuario->Nome->nome();
        $this->genero = $usuario->Genero->valor();
        $this->emailPessoal = $usuario->Email->email();
        $this->enderecoCidade = $usuario->cidade;
        $this->enderecoEstado = $usuario->Estado->uf();

        $base = $this->pegarUsuarioBase();
        $this->setarCpf($valor);
        if (!validarIndiceExiste($base, 'status')) {
            $this->hash = $this->gerarHashCiesc(false, '');
            return;
        }
        if ($base->status == 1) {
            mensagemErro('Conta ativa!', 'Sua conta já está ativa, faça seu login para acessar o clube.');
        }

        $this->hash = $this->gerarHashCiesc(true, $base->id);
    }

    private function gerarHashCiesc(bool $existe, $id): string
    {
        return 'ciesc.' . base64Encode([
            'existe'         => $existe,
            'id'             => $id,
            'cpf'            => $this->cpf->numero(),
            'local_trabalho' => $this->localTrabalho->indice(),
        ]);
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

        if ($this->tipoAtivacao->indice() === TipoAtivacao::CIESC && !$this->localTrabalho->valido()) {
            mensagemErro('Campo inválido!', 'Escolha seu local de trabalho para continuar.');
        } elseif (($this->tipoAtivacao->indice() === TipoAtivacao::CPF) && empty($this->valor)) {
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
        $this->usuario = $this->pegarUsuarioBase();
        $this->validarUsuario($this->usuario);
        $this->criarHash($this->usuario->id);
        $this->setarCpf($this->usuario->cpf);
    }

    private function pegarUsuarioBase()
    {
        $campo = [
            'id', 'cpf', 'nome', 'estado_civil', 'email_pessoal',
            'email_trabalho', 'telefone_celular', 'telefone_fixo',
            'endereco_cep', 'status'
        ];
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($this->empresa);
        $titular = $this->tipoUsuario->indice() === TipoUsuario::TITULAR;
        if ($empresa == 198 && $titular) {
            new CvsHelper(cpf: new Cpf($this->valor));
        }

        return $this
            ->campo($campo)
            ->where($this->pegarWhere($empresa))
            ->primeiro();
    }

    /**
     * @return array[]
     */
    private function pegarWhere($empresa): array
    {
        if (
            (($this->tipoUsuario !== null) && ($this->tipoUsuario->indice() === TipoUsuario::DEPENDENTE)) ||
            $this->tipoAtivacao->indice() === TipoAtivacao::CIESC
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
                'Não foi possível encontrar o seu usuário com os dados fornecidos. Por favor, verifique as informações inseridas e tente novamente.',
                codigo: 4040,
                status: 404
            );
        }

        $status = new Status($usuario->status);
        if (!object_key_exists('id', $usuario) || empty($usuario->id)) {
            mensagemErro(
                'Erro!',
                'Ocorreu um erro ao achar seus dados, por favor, tente novamente.',
                codigo: 5000,
                status: 500
            );
        } elseif ($status->indice() === Status::ATIVO) {
            mensagemErro(
                'Usuário ativo!',
                'Seu usuário já está ativo no sistema, faça seu login para continuar, caso não lembre da sua senha, recupere sua senha ou entre em contato com o atendimento.',
                codigo: 1000,
                status: 403
            );
        } elseif ($status->indice() === Status::BLOQUEADO) {
            mensagemErro('Procure atendimento!', $this->erroGeral, codigo: 4030);
        } elseif ($status->indice() === Status::INDICACAO) {
            mensagemErro(
                'Procure atendimento!',
                'Não é possível ativar um usuário que foi indicado.',
                codigo: 4032,
                status: 401
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
     * @return stdClass
     */
    public function pegarUsuario(): stdClass
    {
        return $this->usuario;
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
