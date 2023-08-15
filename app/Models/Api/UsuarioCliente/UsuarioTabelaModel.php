<?php

namespace App\Models\Api\UsuarioCliente;

use App\Classes\UsuarioCliente\Situacao;
use App\Models\Api\Painel\ConfiguracaoEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\CryptHelper;
use Helpers\ListaHelper;
use Modules\Cpf;
use Modules\Data;
use Modules\Email;
use Modules\Genero;
use Modules\Nome;
use Modules\Telefone;
use ORM\ORM;

final class UsuarioTabelaModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private int $idEmpresa;
    private array $obrigatorio;
    private array $dado;
    private array $retorno = [];
    private array $request;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa('empresa');

        $Configuracao = new ConfiguracaoEntity();
        $this->obrigatorio = $Configuracao->campo_obrigatorio['usuario_cliente'] ?? ['cpf'];
        $this->idEmpresa = TOKEN['empresa']->id;
    }

    /**
     * @return array
     */
    public function retorno(): array
    {
        return $this->retorno;
    }

    /**
     * @param $request
     *
     * @return void
     * @throws Excecao
     */
    public function salvarUsuario($request): void
    {
        $request = (new CryptHelper())->decode($request);
        $this->request = $request;

        if (!is_array($request) || !$request) {
            $this->retorno[] = [false, 'Dados do usuário não poderam ser validados.'];
            return;
        } elseif (!$this->validarCampoObrigatorio()) {
            return;
        }

        $usuario = $this
            ->campo([
                'id', 'cod', 'nome', 'documento', 'siape', 'telefone_celular', 'telefone_fixo', 'email_pessoal',
                'email_trabalho', 'uf', 'cidade', 'aniversario', 'sexo', 'status', 'grupo', 'matricula', 'situacao',
                'federacao'
            ])
            ->where($this->pegarWhereParaBuscar())
            ->primeiro();

        $this->dado = [
            'status'             => 2,
            'mensagem'           => 1,
            'empresa'            => $this->idEmpresa,
            'tipo'               => 1,
            'data_atualizacao'   => agora(),
            'data_upload_tabela' => hoje(),
            'data_dado'          => hoje(),
            'primeiro_acesso'    => 1
        ];

        if (is_object($usuario) && object_key_exists('cod', $usuario)) {
            $this->atualizarUsuarioExistente($usuario);
        } elseif (is_array($usuario) && empty($usuario)) {
            $this->inserirUsuarioNovo();
        }
        $this->retorno[] = [false, 'Ocorreu um erro ao buscar usuário.'];
    }

    /**
     * @return bool
     */
    private function validarCampoObrigatorio(): bool
    {
        $usuario = $this->request;
        $obrigatorio = $this->obrigatorio;

        if (!array_key_exists('nome', $usuario) || empty($usuario['nome'])) {
            $this->retorno[] = [false, 'Você precisa enviar um nome válido.'];
            return false;
        } elseif (
            in_array('cpf', $obrigatorio)
            && (!array_key_exists('cpf', $usuario) || !validarCpf($usuario['cpf']))
        ) {
            $this->retorno[] = [false, 'Você precisa enviar um CPF válido.'];
            return false;
        } elseif (
            in_array('matricula', $obrigatorio)
            && (!array_key_exists('matricula', $usuario) || empty(soNumero($usuario['matricula'])))
        ) {
            $this->retorno[] = [false, 'Você precisa enviar uma matrícula válida.'];
            return false;
        } elseif (
            in_array('siape', $obrigatorio)
            && (!array_key_exists('siape', $usuario) || empty(soNumero($usuario['siape'])))
        ) {
            $this->retorno[] = [false, 'Você precisa enviar um SIAPE válido.'];
            return false;
        }
        return true;
    }

    /**
     * @return array[]
     */
    private function pegarWhereParaBuscar(): array
    {
        $usuario = $this->request;
        $obrigatorio = $this->obrigatorio;

        $where = [['empresa', $this->idEmpresa]];
        if (in_array('cpf', $obrigatorio)) {
            $where[] = ['documento', $usuario['cpf']];
        } elseif (in_array('matricula', $obrigatorio)) {
            $where[] = ['matricula', $usuario['matricula']];
        } elseif (in_array('siape', $obrigatorio)) {
            $where[] = ['siape', $usuario['siape']];
        }

        return $where;
    }

    /**
     * @param $usuario
     *
     * @return void
     * @throws Excecao
     */
    private function atualizarUsuarioExistente($usuario): void
    {
        $dado = [];
        if (in_array($usuario->status, [1, 2])) {
            $dado = $this->montarWhereAtualizandoCampoVazio($usuario);
            $dado['status'] = $usuario->status;
        } elseif (in_array($usuario->status, [3, 5])) {
            $dado = $this->montarWhereComTodosOsCampos($usuario);
        }

        $salvar = $this
            ->dado($dado)
            ->where(['id', $usuario->id])
            ->update();

        if (existeErro($salvar, 'id')) {
            $this->retorno[] = [false, 'Ocorreu um erro ao atualizar o usuário.'];
            return;
        }
        $this->retorno[] = [true, 204];
    }

    /**
     * @param $usuario
     *
     * @return array
     */
    private function montarWhereAtualizandoCampoVazio($usuario): array
    {
        $request = $this->request;

        $listaUf = (new ListaHelper())->uf()->r();
        $Nome = new Nome($request['nome'] ?? '');
        $Cpf = new Cpf($request['cpf'] ?? '');
        $TelefoneCelular = new Telefone($request['telefone_celular'] ?? '');
        $TelefoneFixo = new Telefone($request['telefone_fixo'] ?? '');
        $EmailPessoal = new Email($request['email_pessoal'] ?? '');
        $EmailTrabalho = new Email($request['email_trabalho'] ?? '');
        $DataNascimento = new Data($request['data_nascimento'] ?? '');
        $Genero = new Genero($request['genero'] ?? '');

        $dado = $this->dado;
        if (empty($usuario->nome) && !$Nome->vazio()) {
            $dado['nome'] = $Nome->nome();
        }
        if ((empty($usuario->documento) || !validarCpf($usuario->documento)) && !$Cpf->vazio() && $Cpf->valido()) {
            $dado['documento'] = (int)$Cpf->numero();
        }
        if (empty($usuario->siape) && array_key_exists('siape', $request) && !empty($request['siape'])) {
            $dado['siape'] = (int)soNumero($request['siape']);
        }
        if (empty($usuario->telefone_celular) && !$TelefoneCelular->vazio()) {
            $dado['telefone_celular'] = (int)$TelefoneCelular->numero();
        }
        if (empty($usuario->telefone_fixo) && !$TelefoneFixo->vazio()) {
            $dado['telefone_fixo'] = (int)$TelefoneFixo->numero();
        }
        if (empty($usuario->email_pessoal) && !$EmailPessoal->vazio() && $EmailPessoal->valido()) {
            $dado['email_pessoal'] = $EmailPessoal->email();
            $dado['data_email'] = hoje();
        }
        if (empty($usuario->email_trabalho) && !$EmailTrabalho->vazio() && $EmailTrabalho->valido()) {
            $dado['email_trabalho'] = $EmailTrabalho->email();
            $dado['data_email'] = hoje();
        }
        if (
            empty($usuario->uf)
            && array_key_exists('endereco_estado', $request)
            && !empty($request['endereco_estado']) && in_array($request['endereco_estado'], $listaUf)
        ) {
            $dado['uf'] = strCaixaAlta($request['endereco_estado']);
        }
        if (
            empty($usuario->cidade)
            && array_key_exists('endereco_cidade', $request)
            && !empty($request['endereco_cidade'])
        ) {
            $dado['cidade'] = strCaixaAltaAlta($request['endereco_cidade']);
        }
        if (empty($usuario->aniversario) && !$DataNascimento->vazio() && $DataNascimento->valido()) {
            $dado['aniversario'] = $DataNascimento->date();
        }
        if (empty($usuario->sexo) && !$Genero->vazio() && $Genero->valido()) {
            $dado['sexo'] = $Genero->numero();
        }
        if (empty($usuario->situacao) && array_key_exists('situacao', $request) && !empty($request['situacao'])) {
            $dado['situacao'] = $request['situacao'];
        }
        if (array_key_exists('federacao', $request) && !empty($request['federacao'])) {
            $dado['federacao'] = strCaixaAlta($request['federacao']);
        }
        if (array_key_exists('grupo', $request) && !empty($request['grupo'])) {
            $dado['grupo'] = strCaixaBaixa($request['grupo']);
        }
        if (array_key_exists('matricula', $request) && !empty($request['matricula'])) {
            $dado['matricula'] = $request['matricula'];
        }

        return $dado;
    }

    /**
     * @param $usuario
     *
     * @return array
     */
    private function montarWhereComTodosOsCampos($usuario): array
    {
        $request = $this->request;

        $listaUf = (new ListaHelper())->uf()->r();
        $Nome = new Nome($request['nome'] ?? '');
        $Cpf = new Cpf($request['cpf'] ?? '');
        $TelefoneCelular = new Telefone($request['telefone_celular'] ?? '');
        $TelefoneFixo = new Telefone($request['telefone_fixo'] ?? '');
        $EmailPessoal = new Email($request['email_pessoal'] ?? '');
        $EmailTrabalho = new Email($request['email_trabalho'] ?? '');
        $DataNascimento = new Data($request['data_nascimento'] ?? '');
        $Genero = new Genero($request['genero'] ?? '');
        $Situracao = new Situacao($request['situacao'] ?? '');

        $dado = $this->dado;
        if (!$Nome->vazio()) {
            $dado['nome'] = $Nome->nome();
        }
        if (
            (
                !is_object($usuario)
                || !object_key_exists('documento', $usuario)
                || !validarCpf($usuario->documento)
            )
            && !$Cpf->vazio()
            && $Cpf->valido()
        ) {
            $dado['documento'] = (int)$Cpf->numero();
        }
        if (array_key_exists('siape', $request) && !empty($request['siape'])) {
            $dado['siape'] = (int)soNumero($request['siape']);
        }
        if (!$Situracao->vazio() && $Situracao->valido()) {
            $dado['situacao'] = $Situracao->numero();
        }
        if (!$TelefoneCelular->vazio() && $TelefoneCelular->valido()) {
            $dado['telefone_celular'] = (int)$TelefoneCelular->numero();
        }
        if (!$TelefoneFixo->vazio() && $TelefoneFixo->valido()) {
            $dado['telefone_fixo'] = (int)$TelefoneFixo->numero();
        }
        if (!$EmailPessoal->vazio() && $EmailPessoal->valido()) {
            $dado['email_pessoal'] = $EmailPessoal->email();
        }
        if (!$EmailTrabalho->vazio() && $EmailTrabalho->valido()) {
            $dado['email_trabalho'] = $EmailTrabalho->email();
        }
        if (array_key_exists('endereco_estado', $request) && in_array($request['endereco_estado'], $listaUf)) {
            $dado['uf'] = strCaixaAlta($request['endereco_estado']);
        }
        if (array_key_exists('endereco_cidade', $request) && !empty($request['endereco_cidade'])) {
            $dado['cidade'] = strCaixaAltaAlta($request['endereco_cidade']);
        }
        if (!$DataNascimento->vazio() && $DataNascimento->valido()) {
            $dado['aniversario'] = $DataNascimento->date();
        }
        if (!$Genero->vazio() && $Genero->valido()) {
            $dado['sexo'] = $Genero->numero();
        }
        if (
            array_key_exists('federacao', $request)
            && (in_array($request['federacao'], $listaUf) || $request['federacao'] == 'FU')
        ) {
            $dado['federacao'] = strCaixaAlta($request['federacao']);
        }
        if (array_key_exists('grupo', $request) && !empty($request['grupo'])) {
            $dado['grupo'] = strCaixaBaixa($request['grupo']);
        }
        if (array_key_exists('matricula', $request) && !empty($request['matricula'])) {
            $dado['matricula'] = $request['matricula'];
        }
        if (empty($usuario)) {
            $dado['data_criacao'] = agora();
            $dado['cod'] = uuid();
        }
        return $dado;
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function inserirUsuarioNovo(): void
    {
        $dado = $this->montarWhereComTodosOsCampos([]);

        $salvar = $this
            ->dado($dado)
            ->insert();

        if (existeErro($salvar, 'id')) {
            $this->retorno[] = [false, 'Ocorreu um erro ao salvar o usuário.'];
            return;
        }

        $this->retorno[] = [true, 201];
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | BLOQUEAR USUÁRIO
    |--------------------------------------------------------------------------
    */

    /**
     * @param $hash
     *
     * @return void
     * @throws Excecao
     */
    public function bloquearUsuario($hash): void
    {
        $chave = (new CryptHelper())->decode($hash);
        if (!array_key_exists('chave', $chave) || empty($chave['chave'])) {
            $this->retorno[] = [false, 'Você deve enviar uma chave para bloquear.'];
            return;
        }

        $chave = $chave['chave'];
        $obrigatorio = $this->obrigatorio;

        $where = [['empresa', $this->idEmpresa]];
        if (in_array('cpf', $obrigatorio)) {
            $where[] = ['documento', str_pad($chave, 11, 0, STR_PAD_LEFT)];
        } elseif (in_array('matricula', $obrigatorio)) {
            $where[] = ['documento', soNumero($chave)];
        } elseif (in_array('siape', $obrigatorio)) {
            $where[] = ['siape', soNumero($chave)];
        }

        $usuario = $this->campo(['id'])->where($where)->primeiro();
        if (is_array($usuario) && empty($usuario)) {
            $this->retorno[] = [false, 'Usuário não encontrado para bloquear.'];
            return;
        } elseif (is_object($usuario) && existeErro($usuario, 'id')) {
            $this->retorno[] = [false, 'Erro ao buscar usuário para bloquear.'];
            return;
        }

        $salvar = $this
            ->dado([
                'status'           => 3,
                'data_atualizacao' => agora()
            ])
            ->where(['id', $usuario->id])
            ->update();

        if (existeErro($salvar, 'id')) {
            $this->retorno[] = [false, 'Ocorreu um erro ao salvar o usuário.'];
            return;
        }
        $this->retorno[] = [true, 204];
    }
}
