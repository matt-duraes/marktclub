<?php

namespace ApiModel\PainelHistorico;

use ApiModel\PainelHistorico\Trait\PropriedadeTrait;
use ApiModel\PainelHistorico\Trait\WhereTrait;
use Http\Request;
use App\Models\Api\Download\DownloadGeralModel;

final class DownloadModel extends DownloadGeralModel
{
    use PropriedadeTrait;
    use WhereTrait;

    protected array $campoAceito = [
        'usuario_nome', 'mensagem', 'data_criacao', 'parceiro_nome'
    ];
    protected array $campo;
    protected string $usuario;
    protected string $historico_app;

    public function __construct(
        Request $request
    ) {
        parent::__construct($request, TABELA_PAINEL_HISTORICO, 'painel_historico');
        $this->app = $this->historico_app;
        $this->buscarRegistro();
        $this->validarBusca();
        $this->salvarLogDownload();
        $this->montarRetornoDownload();
        $this->salvarArquivo();
    }

    protected function buscarRegistro(): void
    {
        $campo = $this->converterCampoParaDownload();
        $query = $this
            ->campo($campo)
            ->where($this->pegarWhere());

        $query = $this->pegarQueryUsuario($query);

        $this->busca = $query->read();
    }

    private function converterCampoParaDownload()
    {
        $campo = array_flip($this->campo);
        if (array_key_exists('usuario_nome', $campo)) {
            unset($campo['usuario_nome']);
        }
        if (array_key_exists('parceiro_nome', $campo)) {
            unset($campo['parceiro_nome']);
        }
        return array_keys($campo);
    }

    private function pegarQueryUsuario($query)
    {
        $campoUsuario = [];
        if (in_array('usuario_nome', $this->campo)) {
            $campoUsuario[] = 'nome_real';
        }
        if ($campoUsuario) {
            $query
                ->tabela(TABELA_USUARIO_EQUIPE)
                ->campo($campoUsuario, 'usuario')
                ->leftJoin('id', 'id_usuario_equipe');
        }
        return $query;
    }

    protected function montarRetornoDownload(): void
    {
        $i = 0;
        $retorno = [];
        foreach ($this->busca as $linha) {
            foreach ($linha as $ind => $val) {
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        $this->busca = $retorno;
    }
}
