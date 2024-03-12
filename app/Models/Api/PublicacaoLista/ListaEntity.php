<?php

namespace App\Models\Api\PublicacaoLista;

use ORM\Entity;
use App\Classes\Geral\Status;
use App\Classes\PublicacaoLista\Grupo;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class ListaEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_LISTA;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'lista', 'imagem', 'grupo', 'ordem', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'lista', 'imagem', 'grupo', 'ordem', 'status'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        grupo|Grupo|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    public string $titulo;
    public string $texto;
    public string $imagem;
    public array $lista;
    public int $ordem;
    public Grupo $grupo;
    public Status $status;

    protected function regraSalvar()
    {
        $this->imagem = arquivoPrivadoId($this->imagem);
    }

    protected function regraInsert()
    {
        $this->ordem = 9999;
    }

    protected function regraPosBuscar()
    {
        $this->imagem = !empty($this->imagem) ? arquivoPrivado($this->imagem) : '';
    }
}
