<?php

namespace App\Models\Api\PublicacaoDiretoria;

use ORM\Entity;
use Modules\Nome;
use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class DiretoriaEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_DIRETORIA;
    protected array $ormInsert = [
        'id_admin_empresa'  => '->idEmpresa'
    ];
    protected array $ormSalvar = [
        'nome', 'texto', 'cargo', 'imagem', 'ordem', 'status'
    ];
    protected array $ormBuscar = [
        'nome', 'texto', 'cargo', 'imagem', 'ordem', 'imagem', 'data_criacao', 'data_atualizacao', 'status'
    ];
    protected string $ormValidarSalvar = '
        nome|Nome|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    public Nome $nome;
    public string $texto;
    public string $imagem;
    public string $cargo;
    public Status $status;
    private int $idEmpresa;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
        $this->ormWherePadrao = ['id_admin_empresa', $this->idEmpresa];
    }

    protected function regraSalvar()
    {
        $this->imagem = arquivoPrivadoId($this->imagem);
    }

    protected function regraPosBuscar()
    {
        $this->imagem = !empty($this->imagem) ? arquivoPrivado($this->imagem) : '';
    }
}
