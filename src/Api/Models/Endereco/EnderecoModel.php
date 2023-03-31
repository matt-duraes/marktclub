<?php

namespace ApiModel\Endereco;

use Erro\Excecao;
use Http\Request;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class EnderecoModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_SISTEMA_ENDERECO;
    protected array $ormReplace = [
        'nome' => 'titulo',
        'cod' => 'id_vinculo'
    ];
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
                'uuid',
                'titulo',
                'telefone',
                'cep',
                'logradouro',
                'complemento',
                'referencia',
                'numero',
                'bairro',
                'cidade',
                'estado',
                'pais',
                'latitude',
                'longitude'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), obrigatorio: false)
            ->group('titulo')
            ->order('id', 'DESC')
            ->read();
        ppe($dado);

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

        $returno = [];
        foreach ($dado as $r) {
        }
        return $returno;
    }
}
