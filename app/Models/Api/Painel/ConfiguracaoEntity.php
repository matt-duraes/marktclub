<?php

namespace App\Models\Api\Painel;

use stdClass;
use ORM\Entity;
use Erro\Excecao;
use Helpers\OrmHelper;

final class ConfiguracaoEntity extends Entity
{
    public string $empresa;
    public string $titulo;
    public string $upload_imagem;
    public string $upload_arquivo;
    public string $site_config;
    public array $permissao;
    public array $configuracao;
    public array $campo_obrigatorio;
    public array $campo_permitido;
    public array $upload_grupo;
    protected string $ormTabela = TABELA_PAINEL_CONFIG;
    protected array $ormBuscar = [
        'id_admin_empresa', 'titulo', 'permissao', 'configuracao',
        'campo_obrigatorio', 'campo_permitido', 'upload_grupo'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa' => '->idEmpresa',
        'titulo', 'permissao', 'configuracao', 'campo_obrigatorio',
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
                ['titulo', 'permissao', 'configuracao', 'campo_obrigatorio', 'campo_permitido', 'upload_grupo'],
                'object'
            );

        if (empty($configs)) {
            $configs = (new OrmHelper(TABELA_PAINEL_CONFIG))
                ->pegarUltimoRegistro(
                    ['id_admin_empresa', 0],
                    ['titulo', 'permissao', 'configuracao', 'campo_obrigatorio', 'campo_permitido', 'upload_grupo'],
                    'object'
                );
        }
        return object([
            'titulo'            => $configs->titulo,
            'permissao'         => jsonDecode($configs->permissao, true, true),
            'configuracao'      => jsonDecode($configs->configuracao, true, true),
            'campo_obrigatorio' => jsonDecode($configs->campo_obrigatorio, true, true),
            'campo_permitido'   => jsonDecode($configs->campo_permitido, true, true),
            'upload_grupo'      => jsonDecode($configs->upload_grupo, true, true)
        ]);
    }

    protected function regraPosBuscar(): void
    {
        $campoPermitido = [];
        foreach ($this->campo_permitido as $nomeApp => $recurso) {
            if (array_key_exists('geral', $recurso) && !empty($recurso['geral'])) {
                foreach ($recurso['geral'] as $campo) {
                    $campoPermitido[] = $nomeApp . '-geral-' . $campo;
                }
            }
            if (array_key_exists('download', $recurso) && !empty($recurso['download'])) {
                foreach ($recurso['download'] as $campo) {
                    $campoPermitido[] = $nomeApp . '-download-' . $campo;
                }
            }
        }
        $this->empresa = $this->OrmEmpresa->pegarUuidPeloId($this->id_admin_empresa);
        $this->campo_obrigatorio = $this->campo_obrigatorio['usuario_cliente'];
        $this->campo_permitido = $campoPermitido;
        $this->upload_imagem = $this->upload_grupo['imagem'] ?? '';
        $this->upload_arquivo = $this->upload_grupo['arquivo'] ?? '';
        $this->site_config = $this->upload_grupo['site_config'] ?? '';
    }

    /**
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        $this->validarRequest();
        $campoPermitido = [];
        foreach ($this->campo_permitido as $campo) {
            $appFuncaoCampo = explode('-', $campo);
            $campoPermitido[$appFuncaoCampo[0]][$appFuncaoCampo[1]][] = $appFuncaoCampo[2];
        }
        $this->campo_permitido = $campoPermitido;
        $this->idEmpresa = $this->OrmEmpresa->pegarIdPeloUuid($this->empresa);
        $this->campo_obrigatorio = [
            'usuario_cliente' => $this->campo_obrigatorio
        ];
        $this->upload_grupo = $this->setarUploadGrupo();

        if (empty($this->idEmpresa)) {
            mensagemErro(
                'Empresa não encontrada ou inexistente',
                'Não foi possível salvar por falta de Empresa'
            );
        }

        $this->validarCampoDuplicado(
            propriedade: 'id_admin_empresa',
            campo: 'Painel já cadastrado',
            valor: (string)$this->idEmpresa
        );
    }

    private function setarUploadGrupo(): array
    {
        return [
            'imagem'        => $this->pExiste('upload_imagem') ? $this->upload_imagem : '',
            'arquivo'       => $this->pExiste('upload_arquivo') ? $this->upload_arquivo : '',
            'site_config'   => $this->pExiste('site_config') ? $this->site_config : ''
        ];
    }

    private function validarRequest(): void
    {
        if (empty($this->permissao)) {
            mensagemErro('Campo inválido!', 'As Permissões não podem ser vazias.');
        }
    }
}
