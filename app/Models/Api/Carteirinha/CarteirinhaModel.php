<?php

namespace App\Models\Api\Carteirinha;

use App\Classes\Carteirinha\Ordem;
use App\Classes\Carteirinha\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Erro\Excecao;
use Modules\DataHora;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class CarteirinhaModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_CARTEIRINHA;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $empresa
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $empresa = null,
        private readonly Status $status = new Status()
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @param ClienteEntity $clienteEntity
     *
     * @return array
     * @throws Excecao
     */
    public function gerarCarteirinha(ClienteEntity $clienteEntity): array
    {
        $carteirinhas = $this
            ->campo([
                'texto', 'texto_perdido', 'bg_frente', 'bg_fundo'
            ])
            ->where([
                ['id_admin_empresa', $clienteEntity->id_admin_empresa],
                ['status', (new Status(Status::ATIVO))->numero()]
            ])
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'cod', 'nome_fantasia'
            ], 'empresa')
            ->tabela(TABELA_CONSTRUTOR_CLUBE)
            ->join('id_admin_empresa', 'id_admin_empresa')
            ->campo([
                'uuid', 'logo_principal', 'logo_secundaria'
            ], 'construtor_clube')
            ->read();

        return $this->montarCarteirinha($clienteEntity, $carteirinhas);
    }

    /**
     * @param ClienteEntity $clienteEntity
     * @param array         $carteirinhas
     *
     * @return array
     */
    private function montarCarteirinha(ClienteEntity $clienteEntity, array $carteirinhas): array
    {
        if (empty($carteirinhas)) {
            return $carteirinhas;
        }

        $dataEmissao = new DataHora(agora());
        $retorno = [];
        foreach ($carteirinhas as $carteirinha) {
            $retorno[] = [
                'usuario'      => [
                    'nome'            => $clienteEntity->nome->nome(),
                    'cpf'             => $clienteEntity->cpf->cpf(),
                    'matricula'       => $clienteEntity->matricula,
                    'data_nascimento' => $clienteEntity->data_nascimento->date(),
                    'data_filiacao'   => $clienteEntity->data_termo->date(),
                    'estado'          => $clienteEntity->endereco_estado
                ],
                'empresa'      => [
                    'nome' => $carteirinha->empresa_nome_fantasia
                ],
                'imagem'       => [
                    'logo_principal'  => LINK_ARQUIVO . '/construtor/' . $carteirinha->construtor_clube_logo_principal,
                    'logo_secundaria' => LINK_ARQUIVO . '/construtor/' . $carteirinha->construtor_clube_logo_secundaria,
                    'bg_frente'       => LINK_ARQUIVO . '/construtor/' . $carteirinha->bg_frente,
                    'bg_fundo'        => LINK_ARQUIVO . '/construtor/' . $carteirinha->bg_fundo
                ],
                'data_emissao' => $dataEmissao->date()
            ];
        }
        return $retorno;
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dados = $this
            ->campo([
                'uuid', 'texto', 'texto_perdido', 'bg_frente', 'bg_fundo',
                'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'cod', 'nome_fantasia'
            ], 'empresa')
            ->read();

        $dados->lista = $this->montarRetorno($dados->lista);
        return $dados;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @return array
     */
    private function pegarWhereEmpresa(): array
    {
        $where = [];
        if (!empty($this->empresa)) {
            $where[] = ['cod', $this->empresa];
        }
        return $where;
    }

    /**
     * @param array $carteirinhas
     *
     * @return array
     */
    private function montarRetorno(array $carteirinhas): array
    {
        if (empty($carteirinhas)) {
            return $carteirinhas;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($carteirinhas as $carteirinha) {
            $retorno[] = [
                'id'               => $carteirinha->uuid,
                'empresa'          => [
                    'id'   => $carteirinha->empresa_cod,
                    'nome' => $carteirinha->empresa_nome_fantasia,
                ],
                'texto'            => $carteirinha->texto,
                'texto_perdido'    => $carteirinha->texto_perdido,
                'bg_frente'        => arquivoPublico(LINK_ARQUIVO . '/construtor', $carteirinha->bg_frente ?? ''),
                'bg_fundo'         => arquivoPublico(LINK_ARQUIVO . '/construtor', $carteirinha->bg_fundo ?? ''),
                'status'           => $Status->indice($carteirinha->status),
                'data_criacao'     => $carteirinha->data_criacao,
                'data_atualizacao' => $carteirinha->data_atualizacao
            ];
        }
        return $retorno;
    }
}
