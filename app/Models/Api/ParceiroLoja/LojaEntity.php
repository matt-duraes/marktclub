<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\Entity;
use Modules\Data;
use Helpers\OrmHelper;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\Categoria;
use App\Models\Api\ParceiroLoja\Trait\ValidarTrait;
use App\Models\Api\ParceiroLoja\Trait\PropriedadeTrait;

final class LojaEntity extends Entity
{
    use PropriedadeTrait;
    use ValidarTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    protected array $ormBuscar = [
        'nome_fantasia', 'razao_social', 'tipo_juridico', 'documento_cpf', 'documento_cnpj', 'titulo_interno',
        'tipo_loja', 'id_usuario_equipe', 'responsavel_telefone', 'responsavel_cargo', 'responsavel_nome',
        'responsavel_cpf', 'responsavel_email', 'imagem_logo', 'imagem_capa_desktop', 'imagem_capa_mobile',
        'titulo', 'tipo_estabelecimento', 'origem_lead', 'url', 'delivery', 'convenio_direto', 'data_contrato_inicio',
        'data_contrato_vencimento', 'precisa_aditivo', 'email_contato', 'tipo_procedimento', 'limite_voucher',
        'contato_whatsapp', 'link_site', 'link_alias', 'link_bloqueado', 'texto_descricao', 'texto_desconto',
        'texto_procedimento', 'texto_voucher', 'categoria_principal', 'categoria_lista', 'subcategoria_tag',
        'subcategoria_lista', 'id_admin_empresa', 'destaque', 'endereco_estado', 'pontuacao',
        'prazo_voucher', 'prazo_voucher_fixo', 'data_auditoria', 'confirmar_status', 'confirmar_titulo',
        'confirmar_texto', 'arquivo_painel', 'arquivo_clube', 'status'
    ];
    protected array $ormSalvar = [
        'nome_fantasia', 'razao_social', 'tipo_juridico', 'documento_cpf', 'documento_cnpj', 'titulo_interno',
        'tipo_loja', 'id_usuario_equipe', 'responsavel_telefone', 'responsavel_cargo', 'responsavel_nome',
        'responsavel_cpf', 'responsavel_email', 'imagem_logo', 'imagem_capa_desktop', 'imagem_capa_mobile',
        'titulo', 'tipo_estabelecimento', 'origem_lead', 'url', 'delivery', 'convenio_direto', 'data_contrato_inicio',
        'data_contrato_vencimento', 'precisa_aditivo', 'email_contato', 'tipo_procedimento', 'limite_voucher',
        'contato_whatsapp', 'link_site', 'link_alias', 'link_bloqueado', 'texto_descricao', 'texto_desconto',
        'texto_procedimento', 'texto_voucher', 'categoria_principal', 'categoria_lista', 'subcategoria_tag',
        'subcategoria_lista', 'id_admin_empresa', 'destaque', 'endereco_estado', 'pontuacao',
        'prazo_voucher', 'prazo_voucher_fixo', 'data_auditoria', 'confirmar_status', 'confirmar_titulo',
        'confirmar_texto', 'arquivo_painel', 'arquivo_clube', 'status'
    ];
    private OrmHelper $EmpresaOrm;
    private OrmHelper $EquipeOrm;

    public function __construct()
    {
        parent::__construct();
        $this->EmpresaOrm = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $this->EquipeOrm = new OrmHelper(TABELA_USUARIO_EQUIPE);
    }

    protected function regraInsert()
    {
        $this->status = new Status(Status::PROSPECCAO);
        if (!$this->pExiste('equipe') || empty($this->equipe)) {
            $this->id_usuario_equipe = TOKEN['usuario']->id;
        } elseif ($this->pExiste('equipe') && !empty($this->equipe)) {
            $this->id_usuario_equipe = $this->EquipeOrm->pegarIdPeloUuid($this->equipe);
        }
    }

    protected function regraUpdate()
    {
        $this->id_usuario_equipe = $this->EquipeOrm->pegarIdPeloUuid($this->equipe);
        if ($this->prop('status') != Status::CONCLUIDO && $this->status == Status::CONCLUIDO) {
            $this->data_auditoria = new Data(hoje());
        }
    }

    protected function regraSalvar()
    {
        $this->validarSalvar();
        $this->id_admin_empresa = $this->EmpresaOrm->mudarListaUuidParaId($this->empresa);
        $this->destaque = $this->EmpresaOrm->mudarListaUuidParaId($this->destaque);
        $this->categoria_lista = $this->converterCategoriaEm('numero');
    }

    protected function regraPosBuscar()
    {
        if (empty($this->prazo_voucher) || !preg_match('/^[1-9]{1}[0-9]{0,}$/', $this->prazo_voucher)) {
            $this->prazo_voucher = 10;
        }
        $this->link_site = (new LinkSiteModel($this))->link;
        $this->empresa = $this->EmpresaOrm->mudarListaIdParaUuid($this->id_admin_empresa);
        $this->destaque = $this->EmpresaOrm->mudarListaIdParaUuid($this->destaque);
        $this->equipe = $this->EquipeOrm->pegarUuidPeloId($this->id_usuario_equipe);
        $this->categoria_lista = $this->converterCategoriaEm('indice');
    }

    private function converterCategoriaEm(string $tipo): array
    {
        if (!$this->pExiste('categoria_lista') || empty($this->categoria_lista)) {
            return [];
        }
        $Categoria = new Categoria();
        $lista = [];
        foreach ($this->categoria_lista as $val) {
            $lista[] = $Categoria->$tipo($val);
        }
        return $lista;
    }

    protected function getId()
    {
        return $this->prop('id');
    }
}
