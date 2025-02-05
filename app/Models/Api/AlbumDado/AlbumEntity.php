<?php

namespace App\Models\Api\AlbumDado;

use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Erro;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use Modules\DataHora;
use ORM\Entity;

class AlbumEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $titulo;
    public string $texto;
    public string $imagem;
    public string $url;
    public DataHora $data_inicio;
    public DataHora $data_final;
    public Botao $permissao_restrita;
    public Botao $permissao_site;
    public Status $status;
    public array $foto = [];
    public string $diretorio;
    protected string $ormTabela = TABELA_ALBUM_DADO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'titulo', 'texto', 'imagem', 'url', 'data_inicio',
        'data_final', 'permissao_restrita', 'permissao_site', 'status',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa'  => '->idEmpresa',
        'id_usuario_equipe' => '->idUsuario', 'url'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'imagem', 'data_inicio', 'data_final',
        'permissao_restrita', 'permissao_site', 'status'
    ];
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        data_inicio|Data de Início da Publicação|obrigatorio|vazio|valido
        data_final|Data Final da Publicação|valido
        permissao_restrita|Área Restrita|obrigatorio|vazio
        permissao_site|Público|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $id_admin_empresa;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @return void
     * @throws Erro
     * @throws Excecao
     */
    protected function regraPosBuscar(): void
    {
        if (empty($this->imagem)) {
            $this->setarImagemCapa();
        }
        $this->imagem = arquivoPrivado($this->imagem);
        $this->foto = $this->pegarFotos();

        $ormHelper = new OrmHelper(TABELA_PAINEL_CONFIG);
        $diretorio = $ormHelper->pegarUltimoRegistro(
            ['id_admin_empresa', $this->id_admin_empresa],
            ['upload_grupo']
        );
        $diretorio = jsonDecode($diretorio['upload_grupo'] ?? '', true, true);
        $this->diretorio = empty($diretorio) ? '' : $diretorio['imagem'];
    }

    /**
     * @return void
     * @throws Erro
     * @throws Excecao
     */
    private function setarImagemCapa(): void
    {
        $ormHelper = new OrmHelper(TABELA_ALBUM_FOTO);
        $albumId = $this->prop('id');
        $capa = $ormHelper->pegarPrimeiroRegistro([
            ['id_album_dado', $albumId]
        ], ['imagem']);

        if (empty($capa['imagem'])) {
            return;
        }
        $this->dado(['imagem' => $capa['imagem']])
            ->where(['id', $albumId])
            ->update();
        $this->imagem = $capa['imagem'];
    }

    /**
     * @return array
     * @throws Erro
     * @throws Excecao
     */
    private function pegarFotos(): array
    {
        $ormHelper = new OrmHelper(TABELA_ALBUM_FOTO);
        $fotos = $ormHelper
            ->campo(['uuid', 'titulo'])
            ->where(['id_album_dado', $this->prop('id')])
            ->read();
        return $this->montarRetorno($fotos);
    }

    /**
     * @param array $fotos
     *
     * @return array
     */
    private function montarRetorno(array $fotos): array
    {
        if (empty($fotos)) {
            return [];
        }

        $retorno = [];
        foreach ($fotos as $foto) {
            $retorno[] = [
                'id'     => $foto->uuid,
                'titulo' => $foto->titulo
            ];
        }
        return $retorno;
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->validarDataPassadaInsert();
        //$this->setarUrl();
    }

    /**
     * @throws Excecao
     */
    private function validarDataPassadaInsert(): void
    {
        $hoje = date('Y-m-d');
        if ($hoje > $this->data_inicio->date()) {
            mensagemErro(
                'Acão recusada',
                'A Data de início não pode ser antes da data atual ' . dataBr($hoje),
                localhost: 'A Data de início está no passado. Não existe máquina do tempo ainda. :|'
            );
        }

        if (empty($this->data_final)) {
            return;
        }
        if ($hoje > $this->data_final->date()) {
            mensagemErro(
                'Acão recusada',
                'A Data final não pode ser antes da data atual ' . dataBr($hoje),
                localhost: 'A Data final está no passado. Não existe máquina do tempo ainda. :|'
            );
        }
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function regraUpdate(): void
    {
        $this->validarDataPassadaUpdate();
    }

    /**
     * @throws Excecao
     */
    private function validarDataPassadaUpdate(): void
    {
        $hoje = date('Y-m-d H:i:s');
        $ormHelper = new OrmHelper($this->ormTabela);
        $album = $ormHelper->pegarPrimeiroRegistro(['uuid', $this->id], ['data_inicio', 'data_final']);

        if (($this->data_inicio->date() !== $album['data_inicio']) && ($this->data_inicio->date() < $hoje)) {
            mensagemErro(
                'Acão recusada',
                'A Data de início não pode ser antes da data atual ' . dataBr($hoje),
                localhost: 'A Data de início não pode ser no passado. Você não é viajante do tempo.'
            );
        }

        if ($this->data_final->vazio()) {
            return;
        }
        if (($this->data_final->date() !== $album['data_final']) && ($this->data_final->date() < $hoje)) {
            mensagemErro(
                'Acão recusada',
                'A Data final não pode ser antes da data atual ' . dataBr($hoje),
                localhost: 'A Data final não pode ser no passado. Você não é viajante do tempo.'
            );
        }
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function setarUrl(): void
    {
        $url = strSlug($this->titulo);
        if ($this->checarUrl($url)) {
            $this->url = $url . '-' . uniqid();
            return;
        }
        $this->url = $url;
    }

    /**
     * @param string $url
     *
     * @return bool
     * @throws Excecao
     */
    private function checarUrl(string $url): bool
    {
        $ormHelper = new OrmHelper($this->ormTabela);
        return $ormHelper->existe(['url', $url]);
    }
}
