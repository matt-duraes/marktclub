<?php

namespace App\Models\Api\LoginApi;

use ORM\Entity;
use Modules\Cpf;
use Modules\Data;
use App\Helpers\Cfm\UsuarioHelper;
use App\Models\Api\ApiToken\PayloadModel;
use App\Classes\ApiToken\Tipo as TokenTipo;
use App\Models\Api\LoginApi\Trait\UsuarioTrait;
use App\Models\Api\ApiApp\Trait\AppParaTokenTrait;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;

final class PositivoModel extends Entity
{
    use UsuarioTrait;
    use AppParaTokenTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private array $dadoUsuario;
    private int $idEmpresa;
    private Cpf $Cpf;
    private bool $existe = false;
    private int $idRealUsuario;
    private string $idUsuario = '';
    private int $statusUsuario = 0;
    public array $retorno = [];
    public int $status = 200;
    private string $hash = '';

    public function __construct(
        private array $dado
    ) {
        if (!defined('TOKEN')) {
            mensagemStatus(401);
        }

        $this->Cpf = new Cpf($dado['documento_cpf'] ?? '');
        $this->buscarUsuarioNaApiCFM();
        parent::__construct();
        $this->idEmpresa = 1981;
        $this->verificarSeUsuarioJaExiste();

        $cadastro = $dado['cadastro'] == 'sim';
        if(empty($this->idUsuario) && !$cadastro) {
            $this->montarRetornoCadastro();
            return;
        } elseif (!empty($this->idUsuario)) {
            $this->atualizarUsuarioJaExistente();
            $this->criarToken();
            return;
        }
        $this->salvarNovoUsuario();
        $this->criarToken();
    }

    private function criarToken(): void
    {
        $Usuario = $this->campo([
            'id', 'uuid', 'salt', 'cpf', 'nome', 'imagem', 'email_pessoal', 'email_trabalho', 'tipo',
            'grupo', 'primeiro_acesso', 'mudar_senha', 'data_termo', 'data_criacao', 'data_atualizacao',
            'federacao', 'imagem_arquivo', 'status'
        ])->where(['id', $this->idRealUsuario])->primeiro();

        $App = $this->pegarApp(['uuid', env('API_CLUBE_ID')]);
        $payload = (new PayloadModel($Usuario, $App->audience))->payload;

        $Token = new TokenAuthorizationEntity();
        $this->retorno = $Token->criarToken(
            app: $App,
            body: $payload,
            scope: [],
            audience: $App->audience,
            redirectUri: 'clube.youhuul.com.br',
            state: uuid(),
            empresa: $this->idEmpresa,
            tipo: new TokenTipo(TokenTipo::CLUBE)
        );
    }

    private function buscarUsuarioNaApiCFM()
    {
        $crmNumero = $this->dado['documento_crm'];
        $crmEstado = $this->dado['conselho_estado'];
        $Usuario = new UsuarioHelper(
            tipo: $this->pegarTipo(),
            cpf: $this->Cpf,
            inscricao: $crmNumero,
            estado: $crmEstado,
            dataNascimento: new Data($this->dado['data_nascimento']),
            nomeMae: $this->dado['nome_mae']
        );
        $this->dadoUsuario = [
            'nome' => $Usuario->nome,
            'documento' => $this->Cpf->numero(),
            'email_pessoal' => $Usuario->email_pessoal,
            'email_trabalho' => $Usuario->email_trabalho,
            'crm_numero' => $crmNumero,
            'crm_estado' => $crmEstado
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

    private function montarRetornoCadastro()
    {
        $this->retorno =[
            'cadastro' => 'sim',
            'imutavel' => [
                'nome_completo' => [
                    'nome' => 'Nome completo',
                    'valor' => $this->dadoUsuario['nome'],
                ],
                'documento_cpf' => [
                    'nome' => 'CPF',
                    'valor' => $this->Cpf->cpf()
                ],
                'documento_crm' => [
                    'nome' => $this->pegarTipo() == 'medico' ? 'CRM' : 'Matrícula',
                    'valor' => $this->dadoUsuario['crm_numero'] . '/' . $this->dadoUsuario['crm_estado']
                ],
                'email_pessoal' => [
                    'nome' => 'E-mail pessoal',
                    'valor' => $this->dadoUsuario['email_pessoal']
                ],
            ],
            'opcional' => []
        ];
    }
}
