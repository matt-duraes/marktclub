<?php

namespace App\Models\Api\Cupom;

use App\Classes\Cupom\Ordem;
use App\Classes\Cupom\Status;
use App\Classes\Cupom\Tipo;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use App\Helpers\CupomHelper;

class CupomModel extends ORM
{
    protected string $ormTabela = TABELA_CUPOM_BLOQUEIO;


    public string $idEmpresa;
    /**
     * @param  Request  $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): array
    {
        $CupomHelper = new CupomHelper($this->request);
        $dado  = $CupomHelper->listar();


        if (!$dado) {
            return [];
        }

        return $this->montarRetorno($dado);
    }


    /**
     * @param  array  $dados
     *
     * @return array
     */
    protected function montarRetorno(array $lista): array
    {

        $blackList = $this->listarBloqueado();

        $parceiroBloqueado = [
            6771 => 'oferbox',
        ];

        $retorno = [];
        if ($lista) {
            foreach ($lista as $r) {
                $tipo = $r['tipo'];
                $cupom = $r['cupom'];
                $link = $r['link'];
                $idParceiro = $r['parceiro']['id'] ?? '';
                $nomeParceiro = $r['parceiro']['nome'] ?? '';
                $categoriaParceiro = $r['categoria']['name'] ?? '';

                if (!$this->validarLista($tipo, $cupom)) {
                    $parceiroBloqueado[$idParceiro] = $nomeParceiro;
                }

                if (in_array($this->idEmpresa, ['32']) && $categoriaParceiro == 'Turismo') {
                    $parceiroBloqueado[$idParceiro] = $nomeParceiro;
                }

                if (in_array($idParceiro, array_keys($parceiroBloqueado)) || ($tipo == 'cupom') && isset($blackList[$cupom]) || ($tipo == 'link' && isset($blackList[$link]))) {
                    continue;
                }

                $retorno[] = $r;
            }
        }

        return $retorno;
    }


    public function listarBloqueado(): array
    {
        $bloqueado = $this
            ->campo(['valor'])
            ->where(['data_vencimento', '>=', agora()])
            ->read();

        $array = [];
        if ($bloqueado) {
            foreach ($bloqueado as $r) {
                $array[$r->valor] = $r->valor;
            }
        }
        return $array;

    }

    public function validarLista($tipo, $busca): bool
    {
        if ($tipo == 'link') {
            $tipo = 2;
        } elseif($tipo == 'cupom') {
            $tipo = 1;
        }

        return !$this->existe([
            ['tipo', $tipo],
            ['valor', $busca]
        ]);

    }
}
