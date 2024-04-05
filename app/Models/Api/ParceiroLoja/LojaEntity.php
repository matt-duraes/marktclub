<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\Entity;
use Modules\Data;
use Helpers\OrmHelper;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\Categoria;
use App\Models\Api\Trait\SistemaDataTrait;
use App\Models\Api\ParceiroLoja\Trait\ValidarTrait;
use App\Models\Api\ParceiroLoja\Trait\PropriedadeTrait;

final class LojaEntity extends Entity
{
    use PropriedadeTrait;
    use ValidarTrait;
    use SistemaDataTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    protected array $ormBuscar = [
        'nome_fantasia', 'razao_social', 'tipo_juridico', 'documento_cpf', 'documento_cnpj', 'titulo_interno',
        'tipo_loja', 'id_usuario_equipe', 'responsavel_telefone', 'responsavel_cargo', 'responsavel_nome',
        'responsavel_cpf', 'responsavel_email', 'imagem_logo', 'imagem_capa_desktop', 'imagem_capa_mobile',
        'titulo', 'tipo_estabelecimento', 'origem_lead', 'url', 'delivery', 'convenio_direto', 'data_contrato_inicio',
        'data_contrato_vencimento', 'precisa_aditivo', 'email_contato', 'tipo_procedimento', 'limite_voucher',
        'contato_whatsapp', 'link_site', 'link_alias', 'link_bloqueado', 'texto_descricao', 'texto_desconto',
        'texto_procedimento', 'texto_voucher', 'categoria_principal', 'categoria_lista', 'subcategoria_tag',
        'subcategoria_lista', 'id_admin_empresa', 'destaque', 'endereco_estado', 'pontuacao', 'desconto',
        'prazo_voucher', 'prazo_voucher_fixo', 'data_auditoria', 'confirmar_status', 'confirmar_titulo',
        'confirmar_texto', 'arquivo_painel', 'arquivo_clube', 'data_publicacao', 'status'
    ];
    protected array $ormSalvar = [
        'nome_fantasia', 'razao_social', 'tipo_juridico', 'documento_cpf', 'documento_cnpj', 'titulo_interno',
        'tipo_loja', 'id_usuario_equipe', 'responsavel_telefone', 'responsavel_cargo', 'responsavel_nome',
        'responsavel_cpf', 'responsavel_email', 'imagem_logo', 'imagem_capa_desktop', 'imagem_capa_mobile',
        'titulo', 'tipo_estabelecimento', 'origem_lead', 'url', 'delivery', 'convenio_direto', 'data_contrato_inicio',
        'data_contrato_vencimento', 'precisa_aditivo', 'email_contato', 'tipo_procedimento', 'limite_voucher',
        'contato_whatsapp', 'link_site', 'link_alias', 'link_bloqueado', 'texto_descricao', 'texto_desconto',
        'texto_procedimento', 'texto_voucher', 'categoria_principal', 'categoria_lista', 'subcategoria_tag',
        'subcategoria_lista', 'id_admin_empresa', 'destaque', 'endereco_estado', 'pontuacao', 'desconto',
        'prazo_voucher', 'prazo_voucher_fixo', 'data_auditoria', 'confirmar_status', 'confirmar_titulo',
        'confirmar_texto', 'arquivo_painel', 'arquivo_clube', 'data_publicacao', 'status'
    ];
    private OrmHelper $EmpresaOrm;
    private OrmHelper $EquipeOrm;
    private string $statusInicial;

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

        $statusInicial = (new Status($this->prop('status')))->indice();
        $statusAtual = $this->status->indice();
        if ($statusInicial != Status::CONCLUIDO && $statusAtual == Status::CONCLUIDO) {
            $this->data_auditoria = new Data(hoje());
        }
        if ($statusInicial == Status::PROSPECCAO && $statusAtual == Status::CONCLUIDO) {
            $this->data_publicacao = new Data(hoje());
        }

        $this->statusInicial = $statusInicial;
    }

    protected function regraSalvar()
    {
        $this->validarSalvar();
        $this->id_admin_empresa = $this->EmpresaOrm->mudarListaUuidParaId($this->empresa);
        $this->destaque = $this->EmpresaOrm->mudarListaUuidParaId($this->destaque);
        $this->categoria_lista = $this->converterCategoriaEm('numero');
        $this->validarCampoDuplicado('url', 'url');
        $this->validarCampoDuplicado('titulo_interno', 'Título do painel');
    }

    protected function regraPosInsert()
    {
        $this->sistemaData('Loja cadastrada', 'novo');
    }

    protected function regraPosUpdate()
    {
        $statusInicial = $this->statusInicial;
        $statusAtual = $this->status->indice();
        if ($statusInicial != $statusAtual) {
            $this->salvarMudancaStatus($statusInicial, $statusAtual);
        }
    }

    private function salvarMudancaStatus($statusInicial, $statusAtual)
    {
        $statusGeral = $statusInicial . '_' . $statusAtual;
        $mensagem = [
            Status::PROSPECCAO . '_' . Status::CONCLUIDO     => 'Loja foi publicada',
            Status::PROBLEMA . '_' . Status::CONCLUIDO       => 'Problema foi corrigido',
            Status::CONCLUIDO . '_' . Status::PROBLEMA       => 'Loja com problema',
            Status::CONCLUIDO . '_' . Status::CANCELADO      => 'Loja publicada foi cancelada',
            Status::PROBLEMA . '_' . Status::CANCELADO       => 'Loja com problema foi cancelada',
            Status::PROSPECCAO . '_' . Status::SEM_INTERESSE => 'Loja não teve interrese',
            Status::CANCELADO . '_' . Status::PROSPECCAO     => 'Loja cancelada voltou a prospecção',
            Status::SEM_INTERESSE . '_' . Status::PROSPECCAO => 'Loja sem interesse voltou a prospecção',
            Status::CANCELADO                                => 'A loja foi cancelada',
            Status::CONCLUIDO                                => 'Loja foi publicada',
            Status::PROBLEMA                                 => 'Houve um problema com a loja',
            Status::PROSPECCAO                               => 'Foi recolocada em prospecção',
            Status::SEM_INTERESSE                            => 'Não teve interessem'
        ];
        $indice = $this->status->indice();
        $this->sistemaData($mensagem[$statusGeral] ?? $mensagem[$indice], $statusGeral);
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
