<?php

namespace App\Models\Api\UsuarioIndicacao;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\UsuarioIndicacao\Ordem;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\UsuarioIndicacao\Status;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class IndicacaoModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_USUARIO_INDICACAO;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $pesquisa
     * @param string|null $nome
     * @param string|null $email
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $pesquisa = null,
        private readonly ?string $nome = null,
        private readonly ?string $email = null,
        private readonly Status $status = new Status()
    ) {
        $this->validarDados();
        $this->setarIdEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'nome', 'email', 'status', 'data_criacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /**
     * @return array[]
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

        if (!empty($this->pesquisa)) {
            $where[] = [
                'OR',
                ['nome', 'LIKE', '%' . $this->pesquisa . '%'],
                ['email', 'LIKE', $this->pesquisa . '%']
            ];
        }

        if (!empty($this->nome)) {
            $where[] = ['nome', 'LIKE', '%' . $this->nome . '%'];
        }

        if (!empty($this->email)) {
            $where[] = ['email', 'LIKE', $this->email . '%'];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }

        return $where;
    }

    /**
     * @param array $indicados
     *
     * @return array
     */
    private function montarRetorno(array $indicados): array
    {
        if (empty($indicados)) {
            return $indicados;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($indicados as $indicado) {
            $retorno[] = [
                'id'           => $indicado->uuid,
                'nome'         => $indicado->nome,
                'email'        => $indicado->email,
                'status'       => $Status->indice($indicado->status),
                'data_criacao' => $indicado->data_criacao
            ];
        }
        return $retorno;
    }
}
