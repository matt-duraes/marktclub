<?php

namespace ApiModel\Endereco;

use ORM\ORM;
use Erro\Excecao;
use Modules\Botao;
use Modules\EnderecoEstado;
use System\Classes\Endereco\Tipo;
use System\Classes\Endereco\Local;
use System\Classes\Endereco\Ordem;
use System\Trait\Model\OrdemTrait;

final class EnderecoModel extends ORM
{
    use OrdemTrait;

    protected string $ormTabela = TABELA_SISTEMA_ENDERECO;

    public function __construct(
        private string|array $vinculo,
        private Tipo $tipo,
        private Local $local,
        private ?string $pais = null,
        private ?string $cidade = null,
        private EnderecoEstado $estado = new EnderecoEstado(null),
        private Ordem $ordem = new Ordem(null)
    ) {
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): array
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'telefone', 'cep', 'logradouro', 'complemento', 'referencia',
                'numero', 'bairro', 'cidade', 'estado', 'pais', 'latitude', 'longitude', 'principal'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem())
            ->read();

        return $this->montarRetorno($dado);
    }

    private function pegarWhere(): array
    {
        $where = [];
        if ($this->local->valido()) {
            $where[] = ['local', $this->local->numero()];
        }
        if ($this->tipo->valido()) {
            $where[] = ['tabela', $this->tipo->indice()];
        }
        if (!empty($this->vinculo) && is_array($this->vinculo)) {
            return ['id_vinculo', 'IN', $this->vinculo];
        }
        if (!empty($this->vinculo) && !is_array($this->vinculo)) {
            return ['id_vinculo', $this->vinculo];
        }
        if (!empty($this->pais)) {
            $where[] = ['pais', $this->pais];
        }
        if (!empty($this->cidade)) {
            $where[] = ['cidade', $this->cidade];
        }
        if ($this->estado->valido()) {
            $where[] = ['estado', $this->estado->valor()];
        }
        return $where;
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $r->titulo,
                'telefone'    => $r->telefone,
                'completo'    => $this->formataEnderecoCompleto($r),
                'cep'         => $r->cep,
                'logradouro'  => $r->logradouro,
                'numero'      => $r->numero,
                'complemento' => $r->complemento,
                'referencia'  => $r->referencia,
                'bairro'      => $r->bairro,
                'cidade'      => $r->cidade,
                'estado'      => $r->estado,
                'pais'        => $r->pais,
                'latitude'    => $r->latitude,
                'longitude'   => $r->longitude,
                'principal'   => (new Botao($r->principal))->valor()
            ];
        }
        return $retorno;
    }

    private function formataEnderecoCompleto($endereco)
    {
        $completo = $endereco->logradouro;

        if (!empty($endereco->numero)) {
            $completo .= ' ' . $endereco->numero;
        }
        if (!empty($endereco->complemento)) {
            $completo .= ' ' . $endereco->complemento;
        }
        if (!empty($endereco->referencia)) {
            $completo .= ', ' . $endereco->referencia;
        }
        if (!empty($endereco->bairro)) {
            $completo .= ', ' . $endereco->bairro;
        }
        if (!empty($endereco->cidade)) {
            $completo .= ', ' . $endereco->cidade;
        }
        if (!empty($endereco->estado)) {
            $completo .= !empty($endereco->cidade) ? '/' : ' - ';
            $completo .= $endereco->estado;
        }
        if (!empty($endereco->cep)) {
            $completo .= ' - CEP: ' . strCep($endereco->cep);
        }

        return $completo;
    }
}
