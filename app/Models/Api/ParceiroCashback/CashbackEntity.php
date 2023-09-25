<?php

namespace App\Models\Api\ParceiroCashback;

use App\Classes\ParceiroCashback\Categoria;
use ORM\Entity;
use Modules\Decimal;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;

final class CashbackEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_CASHBACK;
    protected array $ormSalvar = [
        'titulo', 'texto_descricao', 'texto_restricao', 'texto_outro', 'comissao_minima', 'comissao_maxima',
        'status', 'link_site', 'imagem', 'url', 'id_admin_empresa', 'categoria'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto_descricao', 'texto_restricao', 'texto_outro', 'comissao_minima', 'comissao_maxima',
        'status', 'link_site', 'imagem', 'url', 'id_admin_empresa', 'categoria'
    ];
    protected string $ormValidarInsert = '
        titulo|Título|obrigatorio|vazio
        texto_descricao|Texto descrição|obrigatorio|vazio
        texto_restricao|Texto restrição|obrigatorio|vazio
        texto_outro|Texto outro|obrigatorio|vazio
        comissao_minima|Comissão mínima|obrigatorio|vazio|valido
        comissao_maxima|Comissão máxima|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
        categoria|Categoria|obrigatorio|vazio|valido
        link_site|Link site|obrigatorio|vazio
        imagem|Imagem|obrigatorio|vazio
    ';
    public string $titulo;
    public string $texto_descricao;
    public string $texto_restricao;
    public string $texto_outro;
    public Decimal $comissao_minima;
    public Decimal $comissao_maxima;
    public array $empresa;
    public string $link_site;
    public Status $status;
    public string $imagem;
    public string $logo;
    public string $url;
    public Categoria $categoria;
    protected array $id_admin_empresa;

    public function __construct()
    {
        parent::__construct();
    }

    protected function regraSalvar()
    {
        $this->id_admin_empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->mudarListaUuidParaId($this->empresa);
        if ($this->propriedadeExiste('logo') && !empty($this->logo)) {
            $this->logo = arquivoPrivadoId($this->logo);
        }
    }

    protected function regraPosBuscar()
    {
        $this->logo = arquivoPrivado($this->imagem);
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->mudarListaIdParaUuid($this->id_admin_empresa);
    }
}
