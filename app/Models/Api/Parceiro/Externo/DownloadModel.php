<?php

namespace App\Models\Api\Parceiro\Externo;

use ORM\ORM;
use Http\Request;
use App\Classes\ParceiroLoja\Status;
use App\Models\Api\Trait\DownloadModelTrait;
use App\Models\Api\Parceiro\Externo\Trait\Where;
use App\Models\Api\Parceiro\Externo\Trait\Propriedade;

final class DownloadModel extends ORM
{
    use Propriedade;
    use DownloadModelTrait;
    use Where;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    public array $campo;
    public string $usuario;
    private array $campoAceito = [
        'titulo_interno', 'equipe', 'data_criacao', 'data_publicacao', 'status'
    ];

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
    }

    public function listarDados(): array
    {
        $this->validarCampoAceito($this->pExiste('campo') ? $this->campo : [], $this->campoAceito);
        $dado = $this->buscarBanco();
        $this->validarBusca($dado);
        $this->salvarLogDownload($dado, 'parceiro_externo');
        return $this->montarRetornoDownload($dado);
    }

    private function buscarBanco()
    {
        $where = $this->pegarWhere();
        return $this->campo($this->campo)->where($where)->read();
    }

    private function validarBusca(array $dado): void
    {
        if (existeErro($dado, '0')) {
            return;
        }
        $this->erroDownloadPadrao();
    }

    private function montarRetornoDownload(array $dado): array
    {
        $i = 0;
        $retorno = [];
        foreach ($dado as $linha) {
            foreach ($linha as $ind => $val) {
                if ($ind === 'status') {
                    $val = (new Status($val))->indice();
                }
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        return $retorno;
    }
}
