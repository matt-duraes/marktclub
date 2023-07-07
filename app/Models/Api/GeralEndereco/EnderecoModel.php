<?php

namespace App\Models\Api\GeralEndereco;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Http\Request;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

final class EnderecoModel extends ORM
{
    protected string $ormTabela = TABELA_SISTEMA_ENDERECO;
    protected array $ormReplace = [
        'nome' => 'titulo',
        'cod' => 'id_vinculo'
    ];
    private int $idUsuario;

    public function __construct(
        private ?string $uuid = null,
        public ?string $tabela = null,
        public ?string $local = null
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
            ->order('id', 'DESC')
            ->read();

        $dado = $this->montarRetorno($dado);
        return $dado;
    }

    private function pegarWhere(): array
    {
        $where = [];

        $tabela = $this->tabela;
        if (!empty($tabela)) {
            $where[] = ['tabela', $tabela];
        }
        $local = $this->local;
        if (!empty($local)) {
            $where[] = ['local', $local];
        }
        $uuid = $this->uuid;
        if (!empty($uuid)) {
            $where[] = ['uuid', $uuid];
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
                'id' => $r->uuid,
                'titulo' => $r->nome,
                'completo' => $this->formataEnderecoCompleto($r),
                'cep' => strCep($r->cep),
                'logradouro' => $r->logradouro,
                'numero' => $r->numero,
                'complemento' => $r->complemento,
                'referencia' => $r->referencia,
                'bairro' => $r->bairro,
                'cidade' => $r->cidade,
                'estado' => $r->estado,
                'mapa' => (object)[
                    'latitude' => $r->latitude,
                    'longitude' => $r->longitude,
                ],
                'principal' => $r->principal == 1 ? true : false
            ];
        }

        return $retorno;
    }

    private function formataEnderecoCompleto($endereco)
    {

        $completo = $endereco->logradouro;

        if(!empty($endereco->numero)):
            $completo .= ', '.$endereco->numero;
        endif;
        if(!empty($endereco->complemento)):
            $completo .= ', '.$endereco->complemento;
        endif;
        if(!empty($endereco->referencia)):
            $completo .= ', '.$endereco->referencia;
        endif;
        if(!empty($endereco->bairro)):
            $completo .= ' - '.$endereco->bairro;
        endif;
        if(!empty($endereco->cidade)):
            $completo .= ', '.$endereco->cidade;
        endif;
        if(!empty($endereco->estado)):
            $completo .= !empty($endereco->cidade) ? '/' : ' - ';
            $completo .= $endereco->estado;
        endif;

        if(!empty($endereco->cep)):
            $completo .= ' - CEP: '.strCep($endereco->cep);
        endif;

        return $completo;
    }
}
