<?php

namespace App\Models\Api\ComercialPopup;

use App\Classes\ComercialPopup\BotaoTarget;
use App\Classes\ComercialPopup\Status;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\Link;
use ORM\Entity;

class PopupEntity extends Entity
{
    public array $empresa;
    public string $slug;
    public string $imagem;
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
    protected array $ormBuscar = [
        'id_admin_empresa', 'id', 'slug', 'imagem', 'titulo', 'texto', 'regulamento',
        'data_inicio', 'data_final', 'atualizar_dado', 'botao_texto', 'botao_link',
        'botao_target', 'status'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa', 'slug', 'imagem', 'titulo', 'texto', 'regulamento', 'data_inicio',
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
    protected array $id_admin_empresa;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->validarDataPassadaInsert();
        $this->slug = strSlug($this->titulo);
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
    protected function regraUpdate(): void
    {
        $this->validarDataPassadaUpdate();
    }

    /**
     * @throws Excecao
     */
    private function validarDataPassadaUpdate(): void
    {
        $hoje = date('Y-m-d');
        if ($this->data_inicio->date() < $hoje) {
            mensagemErro(
                'Acão recusada',
                'A Data de início não pode ser antes da data atual ' . dataBr($hoje),
                localhost: 'A Data de início não pode ser no passado. Você não é viajante do tempo.'
            );
        }
    }

    protected function regraSalvar(): void
    {
        $this->id_admin_empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->mudarListaUuidParaId($this->empresa);
    }

    protected function regraPosBuscar(): void
    {
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->mudarListaIdParaUuid($this->id_admin_empresa);
    }
}
