<?php

namespace ApiModel\Endereco;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Http\Request;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

final class EnderecoModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_SISTEMA_ENDERECO;
    private int $idUsuario;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    private function validarRequest()
    {
        $request = $this->request;
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'telefone', 'cep', 'logradouro', 'complemento', 'referencia',
                'numero', 'bairro', 'cidade', 'estado', 'pais', 'latitude', 'longitude'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order('id', 'DESC')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function pegarWhere(): array
    {
        $request = $this->request;
        $where = [];

        $titulo = $request->titulo;
        if (!empty($titulo)) {
            $where[] = ['titulo', 'LIKE', '%' . $titulo . '%'];
        }
        return $where;
    }

    private function montarRetorno(array $dado): array
    {
        if (!$dado) {
            return [];
        }

        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid
            ];
        }
        return $retorno;
    }
}
