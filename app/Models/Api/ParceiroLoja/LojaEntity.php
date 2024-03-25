<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\Entity;
use Helpers\OrmHelper;
use App\Models\Api\ParceiroLoja\Trait\PropriedadeTrait;

final class LojaEntity extends Entity
{
    use PropriedadeTrait;

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
        'prazo_voucher', 'prazo_voucher_fixo', 'data_auditoria', 'status'
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
        'prazo_voucher', 'prazo_voucher_fixo', 'data_auditoria', 'status'
    ];
    private OrmHelper $EmpresaOrm;
    private OrmHelper $EquipeOrm;

    public function __construct()
    {
        parent::__construct();
        $this->EmpresaOrm = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $this->EquipeOrm = new OrmHelper(TABELA_USUARIO_EQUIPE);
    }

    protected function regraSalvar()
    {
        $this->id_admin_empresa = $this->EmpresaOrm->mudarListaUuidParaId($this->empresa);
        $this->id_usuario_equipe = $this->EquipeOrm->pegarIdPeloUuid($this->equipe);
    }

    protected function regraPosBuscar()
    {
        if (empty($this->prazo_voucher) || !preg_match('/^[1-9]{1}[0-9]{0,}$/', $this->prazo_voucher)) {
            $this->prazo_voucher = 10;
        }
        $this->imagem_logo = arquivoPrivado($this->imagem_logo);
        $this->imagem_capa_desktop = arquivoPrivado($this->imagem_capa_desktop);
        $this->imagem_capa_mobile = arquivoPrivado($this->imagem_capa_mobile);
        $this->link_site = (new LinkSiteModel($this))->link;
        $this->empresa = $this->EmpresaOrm->mudarListaIdParaUuid($this->id_admin_empresa);
        $this->equipe = $this->EquipeOrm->pegarUuidPeloId($this->id_usuario_equipe);
    }

    protected function getId()
    {
        return $this->prop('id');
    }
}
