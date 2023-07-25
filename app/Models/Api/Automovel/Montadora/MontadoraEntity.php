<?php

namespace App\Models\Api\Automovel\Montadora;

use App\Classes\AutomovelMontadora\Tipo;
use Helpers\UploadHelper;
use Http\Request;
use ORM\Entity;

final class MontadoraEntity extends Entity
{
    public string $cod_parceiro;
    public Tipo $tipo;
    public UploadHelper|string $bg;
    public UploadHelper|string $bg_banner;
    protected string $ormTabela = TABELA_CARRO_MENU;
    protected array $ormBuscar = [
        'uuid', 'cod_parceiro', 'documento', 'tipo', 'titulo', 'bg',
        'bg_banner', 'link_concessionaria', 'procedimento', 'empresa', 'ordem'
    ];
    protected array $ormSalvar = [
        'cod_parceiro', 'documento', 'tipo', 'titulo', 'bg',
        'bg_banner', 'link_concessionaria', 'procedimento', 'empresa', 'ordem'
    ];
    protected string $ormValidarSalvar = '
        cod_parceiro|Uuid Parceiro|obrigatorio|vazio
        ordem|Ordem|obrigatorio|vazio
        bg|Background|obrigatorio|vazio
        titulo|Título|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio|valido
    ';

    public function __construct(
        private ?Request $request = null,
    ) {
        parent::__construct();
    }
}
