<?php

namespace App\Models\Api\Automovel\Versao;

use App\Classes\Geral\Status;
use Helpers\OrmHelper;
use Modules\Dinheiro;
use ORM\Entity;

final class VersaoEntity extends Entity
{
    public string $modelo;
    public string $titulo;
    public string $imagem;
    public string $imagemUrl;
    public string $cor;
    public Dinheiro $valor_de;
    public Dinheiro $valor_por;
    public Status $status;
    protected string $ormTabela = TABELA_AUTOMOVEL_VERSAO;
    protected array $ormBuscar = [
        'titulo', 'imagem', 'cor', 'valor_de', 'valor_por', 'status',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_automovel_modelo'
    ];
    protected array $ormSalvar = [
        'titulo', 'imagem', 'cor', 'valor_de', 'valor_por', 'status'
    ];
    protected string $ormValidarInsert = '
        titulo|Título|vazio|obrigatorio
        imagem|Imagem
        cor|Cor|obrigatorio
        modelo|Modelo|vazio|obrigatorio
        valor_por|Valor por|vazio|obrigatorio|valido
        status|Status|vazio|obrigatorio|valido
    ';
    protected int $id_automovel_modelo;

    protected function regraInsert(): void
    {
        $OrmHelper = new OrmHelper(TABELA_AUTOMOVEL_MODELO);
        $this->id_automovel_modelo = $OrmHelper->pegarIdPeloUuid(
            $this->modelo,
            'O modelo passado não foi encontrado.',
            'Não encontrado!'
        );
    }

    protected function regraPosBuscar(): void
    {
        $this->imagemUrl = arquivoPrivado($this->imagem);
    }
}
