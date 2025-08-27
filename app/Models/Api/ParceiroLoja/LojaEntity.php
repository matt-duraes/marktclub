<?php

namespace App\Models\Api\ParceiroLoja;

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Status;
use App\Classes\SolicitacaoLoja\Status as StatusSolicitacaoLoja;
use App\Models\Api\ParceiroLoja\Trait\PropriedadeTrait;
use App\Models\Api\ParceiroLoja\Trait\ValidarTrait;
use App\Models\Api\SolicitacaoLoja\SolicitacaoEntity;
use App\Models\Api\Trait\SistemaDataTrait;
use Erro\Erro;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use Modules\Data;
use ORM\Entity;
use SendGrid\Mail\TypeException;

final class LojaEntity extends Entity
{
    use PropriedadeTrait;
    use ValidarTrait;
    use SistemaDataTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    protected array $ormRetornoPadrao = ['id', 'titulo', 'imagem_logo'];
    protected array $ormBuscar = [
        'nome_fantasia', 'razao_social', 'tipo_juridico', 'documento_cpf', 'documento_cnpj', 'titulo_interno',
        'tipo_loja', 'id_usuario_equipe', 'responsavel_telefone', 'responsavel_cargo', 'responsavel_nome',
        'responsavel_cpf', 'responsavel_email', 'imagem_logo', 'imagem_capa_desktop', 'imagem_capa_mobile',
        'titulo', 'tipo_estabelecimento', 'origem_lead', 'url', 'delivery', 'convenio_direto',
        'data_contrato_inicio', 'data_contrato_vencimento', 'precisa_aditivo', 'email_contato', 'tipo_procedimento',
        'contato_whatsapp', 'link_site', 'link_alias', 'link_bloqueado', 'texto_descricao', 'texto_desconto',
        'texto_procedimento', 'texto_voucher', 'categoria_principal', 'categoria_lista', 'subcategoria_tag',
        'subcategoria_lista', 'id_admin_empresa', 'destaque', 'endereco_estado', 'pontuacao', 'desconto',
        'prazo_voucher', 'prazo_voucher_fixo', 'data_auditoria', 'confirmar_status', 'confirmar_titulo',
        'confirmar_texto', 'arquivo_painel', 'arquivo_clube', 'data_publicacao', 'status', 'data_prospeccao',
        'comissao_minima', 'comissao_maxima', 'texto_restricao', 'texto_outro', 'data_cancelado', 'data_problema',
        'cancelar_motivo', 'limite_voucher', 'prazo_declaracao'
    ];
    protected array $ormSalvar = [
        'nome_fantasia', 'razao_social', 'tipo_juridico', 'documento_cpf', 'documento_cnpj', 'titulo_interno',
        'tipo_loja', 'id_usuario_equipe', 'responsavel_telefone', 'responsavel_cargo', 'responsavel_nome',
        'responsavel_cpf', 'responsavel_email', 'imagem_logo', 'imagem_capa_desktop', 'imagem_capa_mobile',
        'titulo', 'tipo_estabelecimento', 'origem_lead', 'url', 'delivery', 'convenio_direto',
        'data_contrato_inicio', 'data_contrato_vencimento', 'precisa_aditivo', 'email_contato', 'tipo_procedimento',
        'contato_whatsapp', 'link_site', 'link_alias', 'link_bloqueado', 'texto_descricao', 'texto_desconto',
        'texto_procedimento', 'texto_voucher', 'categoria_principal', 'categoria_lista', 'subcategoria_tag',
        'subcategoria_lista', 'id_admin_empresa', 'destaque', 'endereco_estado', 'pontuacao', 'desconto',
        'prazo_voucher', 'prazo_voucher_fixo', 'data_auditoria', 'confirmar_status', 'confirmar_titulo',
        'confirmar_texto', 'arquivo_painel', 'arquivo_clube', 'data_publicacao', 'status', 'data_prospeccao',
        'comissao_minima', 'comissao_maxima', 'texto_restricao', 'texto_outro', 'data_cancelado', 'data_problema',
        'cancelar_motivo', 'limite_voucher', 'prazo_declaracao'
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

    protected function regraInsert(): void
    {
        $this->status = new Status(Status::PROSPECCAO);
        if (!$this->pExiste('equipe') || empty($this->equipe)) {
            $this->id_usuario_equipe = TOKEN['usuario']->id;
        } elseif ($this->pExiste('equipe') && !empty($this->equipe)) {
            $this->id_usuario_equipe = $this->EquipeOrm->pegarIdPeloUuid($this->equipe);
        }
        $this->data_prospeccao = new Data(hoje());
    }

    protected function regraUpdate(): void
    {
        $this->id_usuario_equipe = $this->EquipeOrm->pegarIdPeloUuid($this->equipe);

        $statusInicial = (new Status($this->prop('status')))->indice();
        $statusAtual = $this->status->indice();

        $statusMudou = $statusInicial != $statusAtual;

        if ($statusInicial == Status::PROSPECCAO && $statusAtual == Status::CONCLUIDO) {
            $this->data_publicacao = new Data(hoje());
        }
        if ($statusMudou && $statusAtual == Status::CONCLUIDO) {
            $this->data_auditoria = new Data(hoje());
        }
        if ($statusMudou && $statusAtual == Status::CANCELADO) {
            $this->data_cancelado = new Data(hoje());
        }
        if ($statusMudou && $statusAtual == Status::PROBLEMA) {
            $this->data_problema = new Data(hoje());
        }
        if ($statusMudou && $statusAtual == Status::PROSPECCAO) {
            $this->data_prospeccao = new Data(hoje());
        }

        $this->statusInicial = $statusInicial;
    }

    protected function regraSalvar(): void
    {
        $this->validarSalvar();
        $this->id_admin_empresa = $this->EmpresaOrm->mudarListaUuidParaId($this->empresa);
        if ($this->pExiste('destaque')) {
            $this->destaque = $this->EmpresaOrm->mudarListaUuidParaId($this->destaque);
        }
        if ($this->pExiste('categoria_lista')) {
            $this->categoria_lista = $this->converterCategoriaEm('numero');
        }
        if ($this->pExiste('subcategoria_lista')) {
            $this->subcategoria_lista = $this->converterUuidParaId($this->subcategoria_lista);
        }
        $this->validarCampoDuplicado('url', 'url');
        $this->validarCampoDuplicado('titulo_interno', 'Título do painel');
        $this->converterComissao();
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

    /**
     * @param array $uuidSubcategorias
     *
     * @return array
     */
    private function converterUuidParaId(array $uuidSubcategorias): array
    {
        $ormHelper = new OrmHelper(TABELA_PARCEIRO_SUBCATEGORIA);
        return $ormHelper->mudarListaUuidParaId($uuidSubcategorias);
    }

    private function converterComissao($float = true): void
    {
        if ($this->pExiste('comissao_minima') && !empty($this->comissao_minima)) {
            $this->comissao_minima = $float
                ? (float)$this->comissao_minima : number_format($this->comissao_minima, 2, '.');
        }
        if ($this->pExiste('comissao_maxima') && !empty($this->comissao_maxima)) {
            $this->comissao_maxima = $float
                ? (float)$this->comissao_maxima : number_format($this->comissao_maxima, 2, '.');
        }
    }

    protected function regraPosInsert(): void
    {
        $this->sistemaData('Loja cadastrada', 'novo');
    }

    /**
     * @return void
     * @throws Erro
     * @throws Excecao
     * @throws TypeException
     */
    protected function regraPosUpdate(): void
    {
        $statusInicial = $this->statusInicial;
        $statusAtual = $this->status->indice();
        if ($statusInicial != $statusAtual) {
            $this->salvarMudancaStatus($statusInicial, $statusAtual);
        }
        $this->mudarStatusIndicacoes();
        //$this->notificarIndicacoes();
    }

    private function salvarMudancaStatus($statusInicial, $statusAtual): void
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
        $this->sistemaData($mensagem[$statusGeral] ?? $mensagem[$indice], $statusGeral, $this->id);
    }

    /**
     * @return void
     * @throws Erro
     * @throws Excecao
     */
    private function mudarStatusIndicacoes(): void
    {
        $indicacoes = $this->pegarIndicacoes();
        if (empty($indicacoes)) {
            return;
        }
        foreach ($indicacoes as $indicacao) {
            $solicitacao = new SolicitacaoEntity();
            $solicitacao->buscar(['id', $indicacao->id], false);
            if ($this->status->indice() === Status::CONCLUIDO) {
                $solicitacao->set('status', StatusSolicitacaoLoja::CONCLUIDO);
            } elseif ($this->status->indice() === Status::PROSPECCAO) {
                $solicitacao->set('status', StatusSolicitacaoLoja::ANDAMENTO);
            } elseif (in_array($this->status->indice(), [Status::CANCELADO, Status::SEM_INTERESSE])) {
                $solicitacao->set('status', StatusSolicitacaoLoja::CANCELADO);
            }
            $solicitacao->salvar();
        }
    }

    /**
     * @return array
     * @throws Erro
     * @throws Excecao
     */
    private function pegarIndicacoes(): array
    {
        $ormHelper = new OrmHelper(TABELA_SOLICITACAO_LOJA);
        return $ormHelper
            ->campo(['id', 'id_admin_empresa', 'id_usuario_cliente'])
            ->where([
                ['id_parceiro_loja', $this->getId()],
                ['status', (new StatusSolicitacaoLoja(StatusSolicitacaoLoja::ANDAMENTO))->numero()]
            ])
            ->read();
    }

    /**
     * @return mixed
     * @throws Erro
     * @throws Excecao
     */
    protected function getId(): mixed
    {
        return $this->prop('id');
    }

    protected function regraPosBuscar(): void
    {
        if (empty($this->prazo_voucher) || !preg_match('/^[1-9]{1}[0-9]{0,}$/', $this->prazo_voucher)) {
            $this->prazo_voucher = 10;
        }
        $this->link_site = (new LinkSiteModel($this))->link;
        $this->empresa = $this->EmpresaOrm->mudarListaIdParaUuid($this->id_admin_empresa);
        $this->destaque = $this->EmpresaOrm->mudarListaIdParaUuid($this->destaque);
        $this->equipe = $this->EquipeOrm->pegarUuidPeloId($this->id_usuario_equipe);
        $this->categoria_lista = $this->converterCategoriaEm('indice');
        $this->subcategoria_lista = $this->converterIdParaUuid($this->subcategoria_lista);
        $this->setarRelacionadoExistem();
        $this->converterComissao(false);
    }

    /**
     * @param array $idSubcategorias
     *
     * @return array
     */
    private function converterIdParaUuid(array $idSubcategorias): array
    {
        $ormHelper = new OrmHelper(TABELA_PARCEIRO_SUBCATEGORIA);
        return $ormHelper->mudarListaIdParaUuid($idSubcategorias);
    }

    private function setarRelacionadoExistem(): void
    {
        $this->existe_endereco = new Botao(
            (new OrmHelper(TABELA_SISTEMA_ENDERECO))->existe([
                ['id_vinculo', $this->id],
                ['local_principal', TABELA_PARCEIRO_LOJA],
                ['local_secundario', 'clube']
            ]) ? 'sim' : 'nao'
        );
        $this->existe_telefone = new Botao(
            (new OrmHelper(TABELA_SISTEMA_CONTATO))->existe([
                ['id_vinculo', $this->id],
                ['local_principal', TABELA_PARCEIRO_LOJA],
                ['local_secundario', 'clube'],
                ['tipo', 1]
            ]) ? 'sim' : 'nao'
        );
        $this->existe_email = new Botao(
            (new OrmHelper(TABELA_SISTEMA_CONTATO))->existe([
                ['id_vinculo', $this->id],
                ['local_principal', TABELA_PARCEIRO_LOJA],
                ['local_secundario', 'clube'],
                ['tipo', 2]
            ]) ? 'sim' : 'nao'
        );
    }

    /**
     * @return void
     * @throws Erro
     * @throws Excecao|TypeException
     */
    private function notificarIndicacoes(): void
    {
        $indicacoes = $this->pegarIndicacoes();
        if (empty($indicacoes)) {
            return;
        }

        $ormHelperUsuario = new OrmHelper(TABELA_USUARIO_CLIENTE);
        foreach ($indicacoes as $indicacao) {
            $usuario = $ormHelperUsuario->pegarUltimoRegistro([
                ['id', $indicacao->id_usuario_cliente],
                ['id_admin_empresa', $indicacao->id_admin_empresa]
            ], ['nome', 'email_pessoal'], 'object');

            $Solicitacao = new SolicitacaoEntity();
            if ($this->status->indice() === Status::CONCLUIDO) {
                $Solicitacao->enviarEmailConcluido(
                    $indicacao->id_admin_empresa,
                    $usuario->nome,
                    $usuario->email_pessoal,
                    $this->titulo
                );
            } elseif (in_array($this->status->indice(), [Status::CANCELADO, Status::SEM_INTERESSE])) {
                $Solicitacao->enviarEmailCancelado(
                    $indicacao->id_admin_empresa,
                    $usuario->nome,
                    $usuario->email_pessoal,
                    $this->titulo
                );
            }
        }
    }
}
