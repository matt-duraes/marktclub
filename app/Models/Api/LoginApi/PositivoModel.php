<?php

namespace App\Models\Api\LoginApi;

use ORM\Entity;
use Modules\Cpf;
use Modules\Data;
use App\Helpers\Cfm\UsuarioHelper;

final class PositivoModel extends Entity
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private array $dadoUsuario;
    private int $idEmpresa;
    private Cpf $Cpf;
    private bool $cadastro;
    private bool $existe = false;
    private int $idReal;

    public function __construct(
        private array $dado
    ) {
        if (!defined('TOKEN')) {
            mensagemStatus(401);
        }
        $this->cadastro = $dado['cadastro'] == 'sim';
        $this->Cpf = new Cpf($dado['documento_cpf'] ?? '');
        $this->buscarUsuarioNaApiCFM();
        parent::__construct();
        $this->idEmpresa = 1981;
        $this->buscarUsuarioNaBase();

        // $this->buscarUsuarioNaApiDigio();

        // $this->verificarSeUsuarioJaExiste();
        // if (!empty($this->idUsuario)) {
        //     $this->atualizarUsuarioJaExistente();
        //     return;
        // }
        // $this->salvarNovoUsuario();
    }

    private function buscarUsuarioNaApiCFM()
    {
        $Usuario = new UsuarioHelper(
            tipo: $this->pegarTipo(),
            cpf: $this->Cpf,
            inscricao: $this->dado['documento_crm'],
            estado: $this->dado['conselho_estado'],
            dataNascimento: new Data($this->dado['data_nascimento']),
            nomeMae: $this->dado['nome_mae']
        );
        $this->dadoUsuario = [
            'nome' => $Usuario->nome,
            'email_pessoal' => $Usuario->email_pessoal,
            'email_trabalho' => $Usuario->email_trabalho
        ];
    }

    private function pegarTipo(): string
    {
        $tipo = strCaixaBaixa($this->dado['tipo_usuario'] ?? '');
        return [
            'medico' => 'medico',
            'funcionario' => 'funcionario',
            'm' => 'medico',
            'f' => 'funcionario'
        ][$tipo] ?? 'medico';
    }

    private function buscarUsuarioNaBase()
    {
        $usuario = $this
            ->campo(['id'])
            ->where([
                ['id_admin_empresa', $this->idEmpresa],
                ['documento', $this->Cpf]
            ])
            ->primeiro(retorno: 'object');
        if(!is_array($usuario) || !array_key_exists('id', $usuario)) {
            return;
        }
        $this->existe = true;
        $this->idReal = $usuario['id'];
    }

    // private function buscarUsuarioNaApiDigio()
    // {
    //     if (eLocalhost()) {
    //         $this->dadoUsuario = [
    //             'nome'          => 'André Rodrigues',
    //             'email_pessoal' => 'andre@youhuul.com',
    //             'documento'     => '01495180131'
    //         ];
    //         return;
    //     }
    //     $Digio = new DigioHelper($this->usuario);
    //     $this->dadoUsuario = $Digio->usuario();
    // }
}
