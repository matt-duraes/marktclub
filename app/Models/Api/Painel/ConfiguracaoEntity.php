<?php

namespace App\Models\Api\Painel;

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
        return object([
            'permissao'         => jsonDecode($configs->permissao, true, true),
            'configuracao'      => jsonDecode($configs->configuracao, true, true),
            'campo_obrigatorio' => jsonDecode($configs->campo_obrigatorio, true, true),
            'campo_permitido'   => jsonDecode($configs->campo_permitido, true, true),
            'upload_grupo'      => jsonDecode($configs->upload_grupo, true, true)
        ]);
    }

    protected function regraPosBuscar(): void
    {
        $permissoes = [];
        foreach ($this->permissao as $permissoesApp) {
            if (array_key_exists('permissao', $permissoesApp)) {
                foreach (array_keys($permissoesApp['permissao']) as $permissao) {
                    $permissoes[] = $permissao;
                }
            }
        }

        $this->permissao = $permissoes;
        $this->empresa = $this->OrmEmpresa->pegarUuidPeloId($this->id_admin_empresa);
        $this->campo_obrigatorio = $this->campo_obrigatorio['usuario_cliente'];
    }

    protected function regraSalvar(): void
    {
        $acoes = [];
        $permissoes = [];
        foreach ($this->permissao as $permissao) {
            $acao = '';
            $app = '';
            if (str_ends_with($permissao, 'index')) {
                $acao = 'Listar';
                $app = str_replace('_index', '', $permissao);
            } elseif (str_ends_with($permissao, 'add')) {
                $acao = 'Salvar';
                $app = str_replace('_add', '', $permissao);
            } elseif (str_ends_with($permissao, 'editar')) {
                $acao = 'Editar';
                $app = str_replace('_editar', '', $permissao);
            } elseif (str_ends_with($permissao, 'visualizar')) {
                $acao = 'Visualizar';
                $app = str_replace('_visualizar', '', $permissao);
            } elseif (str_ends_with($permissao, 'deletar')) {
                $acao = 'Deletar';
                $app = str_replace('_deletar', '', $permissao);
            } elseif (str_ends_with($permissao, 'download')) {
                $acao = 'Download';
                $app = str_replace('_download', '', $permissao);
            } elseif (str_ends_with($permissao, 'empresa')) {
                $acao = 'Todas as Empresas';
                $app = str_replace('_empresa', '', $permissao);
            } elseif (str_ends_with($permissao, 'analytics')) {
                $acao = 'Analytics';
                $app = str_replace('_analytics', '', $permissao);
            } elseif (str_ends_with($permissao, 'apple')) {
                $acao = 'Apple';
                $app = str_replace('_apple', '', $permissao);
            } elseif (str_ends_with($permissao, 'status')) {
                $acao = 'Status';
                $app = str_replace('_status', '', $permissao);
            } elseif (str_ends_with($permissao, 'permissao')) {
                $acao = 'Todas as permissões';
                $app = str_replace('_permissao', '', $permissao);
            } elseif (str_ends_with($permissao, 'salvar')) {
                $acao = 'Cadastrar usuário';
                $app = str_replace('_salvar', '', $permissao);
            } elseif (str_ends_with($permissao, 'bloquear')) {
                $acao = 'Bloquear usuário';
                $app = str_replace('_bloquear', '', $permissao);
            } elseif (str_ends_with($permissao, 'tecnologia')) {
                $acao = 'Tecnologia';
                $app = str_replace('_tecnologia', '', $permissao);
            } elseif (str_ends_with($permissao, 'criacao')) {
                $acao = 'Criacão';
                $app = str_replace('_criacao', '', $permissao);
            } elseif (str_ends_with($permissao, 'convenio')) {
                $acao = 'Convenio';
                $app = str_replace('_convenio', '', $permissao);
            }
            $acoes[$app][] = $permissao;
            $acoes[$app][$permissao] = $acao;
        }

        foreach ($acoes as $app => $acao) {
            $permissoes[$app] = [
                'titulo'    => $this->titulo[$app] ?? '',
                'permissao' => $acao
            ];
        }

        $this->permissao = $permissoes;
        $this->idEmpresa = $this->OrmEmpresa->pegarIdPeloUuid($this->empresa);
        $this->campo_obrigatorio = [
            'usuario_cliente' => $this->campo_obrigatorio
        ];
    }
}
