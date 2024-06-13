<?php

namespace App\Models\Api\Parceiro\Externo;

use Http\Request;
use App\Classes\ParceiroLoja\Status;
use App\Models\Api\Download\DownloadGeralModel;
use App\Models\Api\Parceiro\Externo\Trait\WhereTrait;
use App\Models\Api\Parceiro\Externo\Trait\ValidarTrait;
use App\Models\Api\Parceiro\Externo\Trait\PropriedadeTrait;

final class DownloadModel extends DownloadGeralModel
{
    use PropriedadeTrait;
    use WhereTrait;
    use ValidarTrait;

    protected array $campoAceito = [
        'titulo_interno', 'equipe', 'data_criacao', 'data_publicacao', 'status'
    ];

    public function __construct(
        Request $request
    ) {
        parent::__construct($request, TABELA_PARCEIRO_LOJA, 'parceiro-externo');
        $this->validarRequest();
        $this->buscarRegistro();
        $this->validarBusca();
        $this->salvarLogDownload();
        $this->montarRetornoDownload();
        $this->salvarArquivo();
    }

    protected function buscarRegistro(): void
    {
        $where = $this->pegarWhere();
        $this->busca = $this->campo($this->campo)->where($where)->read();
    }

    protected function montarRetornoDownload(): void
    {
        $i = 0;
        $retorno = [];
        foreach ($this->busca as $linha) {
            foreach ($linha as $ind => $val) {
                if ($ind === 'status') {
                    $val = (new Status($val))->indice();
                }
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        $this->busca = $retorno;
    }
}
