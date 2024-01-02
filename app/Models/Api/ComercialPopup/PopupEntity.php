<?php

namespace App\Models\Api\ComercialPopup;

use App\Classes\ComercialPopup\BotaoTarget;
use App\Classes\ComercialPopup\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\Link;
use ORM\Entity;

class PopupEntity extends Entity
{
    public array $empresa;
    public string $slug;
    public array $usuario_tipo;
    public string $imagem;
    public string $titulo;
    public string $texto;
    public string $regulamento;
    public string $titulo_painel;
    public Data $data_inicio;
    public Data $data_final;
    public string $atualizar_dado;
    public string $botao_texto;
    public Link $botao_link;
    public BotaoTarget $botao_target;
    public Status $status;
    protected string $ormTabela = TABELA_COMERCIAL_POPUP;
    protected array $ormBuscar = [
        'id_admin_empresa', 'titulo_painel', 'usuario_tipo', 'slug', 'imagem', 'titulo', 'texto', 'regulamento',
        'data_inicio', 'data_final', 'atualizar_dado', 'botao_texto', 'botao_link',
        'botao_target', 'status'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa', 'titulo_painel', 'usuario_tipo', 'slug', 'imagem', 'titulo', 'texto', 'regulamento', 'data_inicio',
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

        if ($hoje > $this->data_final->date()) {
            mensagemErro(
                'Acão recusada',
                'A Data final não pode ser antes da data atual ' . dataBr($hoje),
                localhost: 'A Data final está no passado. Não existe máquina do tempo ainda. :|'
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
        $popup = (new OrmHelper($this->ormTabela))
            ->pegarPrimeiroRegistro(['uuid', $this->id], ['data_inicio', 'data_final']);

        if (($this->data_inicio->date() != $popup['data_inicio']) && ($this->data_inicio->date() < $hoje)) {
            mensagemErro(
                'Acão recusada',
                'A Data de início não pode ser antes da data atual ' . dataBr($hoje),
                localhost: 'A Data de início não pode ser no passado. Você não é viajante do tempo.'
            );
        }

        if (($this->data_final->date() != $popup['data_final']) && ($this->data_final->date() < $hoje)) {
            mensagemErro(
                'Acão recusada',
                'A Data final não pode ser antes da data atual ' . dataBr($hoje),
                localhost: 'A Data final não pode ser no passado. Você não é viajante do tempo.'
            );
        }
    }

    protected function regraSalvar(): void
    {
        $this->pegarNumeroUsuarioTipo();
        $this->id_admin_empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->mudarListaUuidParaId($this->empresa);
    }

    private function pegarNumeroUsuarioTipo(): void
    {
        $usuario_tipo = [];
        foreach ($this->usuario_tipo as $tipo) {
            $usuario_tipo[] = (new TipoUsuario())->numero($tipo);
        }
        $this->usuario_tipo = $usuario_tipo;
    }

    protected function regraPosSalvar(): void
    {
        $this->pegarIndiceUsuarioTipo();
    }

    private function pegarIndiceUsuarioTipo(): void
    {
        $usuario_tipo = [];
        foreach ($this->usuario_tipo as $tipo) {
            $usuario_tipo[] = (new TipoUsuario())->indice($tipo);
        }
        $this->usuario_tipo = $usuario_tipo;
    }

    protected function regraPosBuscar(): void
    {
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->mudarListaIdParaUuid($this->id_admin_empresa);
        $this->pegarIndiceUsuarioTipo();
    }
}
