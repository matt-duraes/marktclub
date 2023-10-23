<?php

namespace App\Models\Api\ComercialPopup;

use App\Classes\ComercialPopup\BotaoTarget;
use App\Classes\ComercialPopup\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Helpers\UploadHelper;
use Modules\Data;
use Modules\Link;
use ORM\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PopupEntity extends Entity
{
    use ValidarEmpresaTrait;

    public ?int $id_form_popup;
    public string $slug;
    public UploadedFile|UploadHelper|string $imagem;
    public string $titulo;
    public string $texto;
    public string $regulamento;
    public Data $data_inicio;
    public Data $data_final;
    public string $atualizar_dado;
    public string $botao_texto;
    public Link $botao_link;
    public BotaoTarget $botao_target;
    public Status $status;
    protected string $ormTabela = TABELA_COMERCIAL_POPUP;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormBuscar = [
        'id_admin_empresa', 'id', 'slug', 'imagem', 'titulo', 'texto', 'regulamento',
        'data_inicio', 'data_final', 'atualizar_dado', 'botao_texto', 'botao_link',
        'botao_target', 'status'
    ];
    protected array $ormSalvar = [
        'slug', 'imagem', 'titulo', 'texto', 'regulamento', 'data_inicio',
        'data_final', 'atualizar_dado', 'botao_texto', 'botao_link',
        'botao_target', 'status'
    ];
    protected string $ormValidarSalvar = '
        imagem|Imagem|valido
        titulo|Título|obrigatorio|vazio
        texto|Conteúdo
        regulamento|Regulamento
        data_inicio|Data Início|obrigatorio|vazio|valido
        data_final|Data Final|valido
        atualizar_dado|Dados Atualizar
        botao_texto|Texto do Botão
        botao_link|Link do Botão|valido
        botao_target|Tipo de Link|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected ?int $idEmpresa;
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
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        $this->validarDataPassadaInsert();
        $this->slug = strSlug($this->titulo);
        $this->status = new Status(Status::INATIVO);
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
    }

    /**
     * @throws Excecao
     */
    public function regraUpdate(): void
    {
        $this->validarDataPassadaUpdate();
    }

    /**
     * @throws Excecao
     */
    private function validarDataPassadaUpdate(): void
    {
        $popup = (new OrmHelper($this->ormTabela))
            ->pegarPrimeiroRegistro(['uuid', $this->id], ['data_inicio']);

        if ($this->data_inicio->date() < $popup['data_inicio']) {
            mensagemErro(
                'Acão recusada',
                'A Data de início não pode ser menor do que há atual',
                localhost: 'A Data de início não pode ser no passado. Você não é viajante do tempo.'
            );
        }
    }

    /**
     * @throws Excecao
     */
    public function regraSalvar(): void
    {
        //$this->validarLimitePopup();
    }

    /**
     * @throws Excecao
     */
    private function validarLimitePopup(): void
    {
        $popups = (new OrmHelper($this->ormTabela))->pegarListaCampo(
            [
                ['id_admin_empresa', $this->idEmpresa],
                ['status', (new Status(Status::ATIVO))->numero()]
            ],
            'id'
        );

        if (count($popups) >= 1) {
            mensagemErro(
                'Acão recusada',
                'Número limite de popups ativo atingido',
                localhost: 'Já existe um popup ativo no momento.'
            );
        }
    }
}
