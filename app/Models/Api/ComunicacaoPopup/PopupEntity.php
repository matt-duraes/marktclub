<?php

namespace App\Models\Api\ComunicacaoPopup;

use App\Classes\ComunicacaoPopup\BotaoTarget;
use App\Classes\ComunicacaoPopup\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
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
    protected string $ormTabela = TABELA_COMUNICACAO_POPUP;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormBuscar = [
        'id', 'slug', 'imagem', 'titulo', 'texto', 'regulamento', 'data_inicio',
        'data_final', 'atualizar_dado', 'botao_texto', 'botao_link',
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

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        $this->slug = strSlug($this->titulo);
        $this->status = new Status(Status::INATIVO);
    }
}
