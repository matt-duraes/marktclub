<?php

namespace App\Models\Api\Painel;

use Erro\Excecao;
use Helpers\OrmHelper;
use ORM\Entity;
use stdClass;

final class ConfiguracaoEntity extends Entity
{
    public string $empresa;
    public array $titulo;
    public array $permissao;
    public array $configuracao;
    public array $campo_obrigatorio;
    public array $campo_permitido;
    public array $upload_grupo;
    protected string $ormTabela = TABELA_PAINEL_CONFIG;
    protected array $ormBuscar = [
        'id_admin_empresa', 'permissao', 'configuracao',
        'campo_obrigatorio', 'campo_permitido', 'upload_grupo'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa' => '->idEmpresa',
        'permissao', 'configuracao', 'campo_obrigatorio',
        'campo_permitido', 'upload_grupo'
    ];
    protected int $idEmpresa;
    protected int $id_admin_empresa;
    private OrmHelper $OrmEmpresa;

    public function __construct()
    {
        $this->OrmEmpresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        parent::__construct();
    }

    public function pegarConfiguracoes(): stdClass
    {
        $configs = (new OrmHelper(TABELA_PAINEL_CONFIG))
            ->pegarUltimoRegistro(
                ['id_admin_empresa', TOKEN['empresa']->id],
                ['permissao', 'configuracao', 'campo_obrigatorio', 'campo_permitido', 'upload_grupo'],
                'object'
            );

        if (empty($configs)) {
            $configs = (new OrmHelper(TABELA_PAINEL_CONFIG))
                ->pegarUltimoRegistro(
                    ['id_admin_empresa', 0],
                    ['permissao', 'configuracao', 'campo_obrigatorio', 'campo_permitido', 'upload_grupo'],
                    'object'
                );
        }
        return object([
            'permissao'          => jsonDecode($configs->permissao, true, true),
            'configuracao'       => jsonDecode($configs->configuracao, true, true),
            'campo_obrigatorio'  => jsonDecode($configs->campo_obrigatorio, true, true),
            'campo_permitido'    => jsonDecode($configs->campo_permitido, true, true),
            'upload_grupo'       => jsonDecode($configs->upload_grupo, true, true)
        ]);
    }

    protected function regraPosBuscar(): void
    {
        $permissoes = [];
        foreach ($this->permissao as $nomeApp => $permissoesApp) {
            if (array_key_exists('acao', $permissoesApp) && !empty($permissoesApp['acao'])) {
                foreach ($permissoesApp['acao'] as $permissao) {
                    $permissoes[] = $nomeApp . '_' . $permissao;
                }
            } elseif (array_key_exists('permissao', $permissoesApp)) {
                foreach (array_keys($permissoesApp['permissao']) as $permissao) {
                    $permissoes[] = $permissao;
                }
            }
        }

        $this->permissao = array_unique($permissoes);
        $this->empresa = $this->OrmEmpresa->pegarUuidPeloId($this->id_admin_empresa);
        $this->campo_obrigatorio = $this->campo_obrigatorio['usuario_cliente'];
    }

    /**
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        $apps = [];
        $acoes = [];
        $permissoes = [];
        foreach ($this->permissao as $permissao) {
            $acao = '';
            $tituloPermissao = '';
            $nomeApp = '';
            if (str_ends_with($permissao, 'index')) {
                $acao = 'index';
                $tituloPermissao = 'Listar';
                $nomeApp = str_replace('_index', '', $permissao);
            } elseif (str_ends_with($permissao, 'add')) {
                $acao = 'add';
                $tituloPermissao = 'Salvar';
                $nomeApp = str_replace('_add', '', $permissao);
            } elseif (str_ends_with($permissao, 'editar')) {
                $acao = 'editar';
                $tituloPermissao = 'Editar';
                $nomeApp = str_replace('_editar', '', $permissao);
            } elseif (str_ends_with($permissao, 'visualizar')) {
                $acao = 'visualizar';
                $tituloPermissao = 'Visualizar';
                $nomeApp = str_replace('_visualizar', '', $permissao);
            } elseif (str_ends_with($permissao, 'deletar')) {
                $acao = 'deletar';
                $tituloPermissao = 'Deletar';
                $nomeApp = str_replace('_deletar', '', $permissao);
            } elseif (str_ends_with($permissao, 'download')) {
                $acao = 'download';
                $tituloPermissao = 'Download';
                $nomeApp = str_replace('_download', '', $permissao);
            } elseif (str_ends_with($permissao, 'empresa')) {
                $acao = 'empresa';
                $tituloPermissao = 'Todas as Empresas';
                $nomeApp = str_replace('_empresa', '', $permissao);
            } elseif (str_ends_with($permissao, 'parceiro')) {
                $acao = 'parceiro';
                $tituloPermissao = 'Todos os Parceiros';
                $nomeApp = str_replace('_parceiro', '', $permissao);
            } elseif (str_ends_with($permissao, 'analytics')) {
                $acao = 'analytics';
                $tituloPermissao = 'Analytics';
                $nomeApp = str_replace('_analytics', '', $permissao);
            } elseif (str_ends_with($permissao, 'apple')) {
                $acao = 'apple';
                $tituloPermissao = 'Apple';
                $nomeApp = str_replace('_apple', '', $permissao);
            } elseif (str_ends_with($permissao, 'status')) {
                $acao = 'status';
                $tituloPermissao = 'Status';
                $nomeApp = str_replace('_status', '', $permissao);
            } elseif (str_ends_with($permissao, 'permissao')) {
                $acao = 'permissao';
                $tituloPermissao = 'Todas as Permissões';
                $nomeApp = str_replace('_permissao', '', $permissao);
            } elseif (str_ends_with($permissao, 'salvar')) {
                $acao = 'salvar';
                $tituloPermissao = 'Cadastrar usuário';
                $nomeApp = str_replace('_salvar', '', $permissao);
            } elseif (str_ends_with($permissao, 'bloquear')) {
                $acao = 'bloquear';
                $tituloPermissao = 'Bloquear usuário';
                $nomeApp = str_replace('_bloquear', '', $permissao);
            } elseif (str_ends_with($permissao, 'tecnologia')) {
                $acao = 'tecnologia';
                $tituloPermissao = 'Tecnologia';
                $nomeApp = str_replace('_tecnologia', '', $permissao);
            } elseif (str_ends_with($permissao, 'criacao')) {
                $acao = 'criacao';
                $tituloPermissao = 'Criação';
                $nomeApp = str_replace('_criacao', '', $permissao);
            } elseif (str_ends_with($permissao, 'convenio')) {
                $acao = 'convenio';
                $tituloPermissao = 'Convenio';
                $nomeApp = str_replace('_convenio', '', $permissao);
            }
            $apps[] = $nomeApp;
            $acoes[$nomeApp][] = $acao;
            $permissoes[$nomeApp][$permissao] = $tituloPermissao;
        }

        $painelPermissao = [];
        foreach ($apps as $nomeApp) {
            $painelPermissao[$nomeApp] = [
                'titulo'    => $this->titulo[$nomeApp] ?? '',
                'acao'      => $acoes[$nomeApp],
                'permissao' => $permissoes[$nomeApp]
            ];
        }

        $this->permissao = $painelPermissao;
        $this->idEmpresa = $this->OrmEmpresa->pegarIdPeloUuid($this->empresa);
        $this->campo_obrigatorio = [
            'usuario_cliente' => $this->campo_obrigatorio
        ];

        if (empty($this->idEmpresa)) {
            mensagemErro(
                'Empresa não encontrada ou inexistente',
                'Não foi possível salvar por falta de Empresa'
            );
        }
        $this->validarCampoDuplicado(
            campo: 'id_admin_empresa',
            mensagem: 'Painel já cadastrado',
            valor: $this->idEmpresa
        );
    }
}
