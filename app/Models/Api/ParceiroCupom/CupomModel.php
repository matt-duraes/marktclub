<?php

namespace App\Models\Api\ParceiroCupom;

use ORM\ORM;
use Erro\Excecao;
use Http\Request;
use App\Helpers\CupomHelper;

class CupomModel extends ORM
{
    protected string $ormTabela = TABELA_PARCEIRO_CUPOM_BLOQUEADO;
    public string $idEmpresa;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        parent::__construct();
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->id : 1;
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws Excecao
     */
    public function listarDados(Request $request): array
    {
        $CupomHelper = new CupomHelper($request);
        $dado = $CupomHelper->listar();

        if (!$dado) {
            return [];
        }

        return $this->montarRetorno($dado);
    }

    /**
     * @param  string  $id
     * @return array
     * @throws Excecao
     */
    public function buscarDados($id): array
    {
        $dado = (new CupomHelper())->buscar($id);

        if (!is_array($dado) || !isset($dado['id'])) {
            mensagemStatus(404);
        }

        $busca = $dado['cupom'];
        $tipo = 1;
        if ($dado['tipo'] == 'link') {
            $busca = $dado['link'];
            $tipo = 2;
        }

        if (!$this->validarLista($tipo, $busca)) {
            mensagemStatus(404);
        }

        return $dado;
    }

    /**
     * @param array $dados
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
        return $retorno;
    }

    public function listarBloqueado(): array
    {
        return $this->pegarSelect('valor', 'valor', ['data_vencimento', '>=', agora()]);
    }

    public function validarLista($tipo, $busca): bool
    {
        if ($tipo == 'link') {
            $tipo = 2;
        } elseif ($tipo == 'cupom') {
            $tipo = 1;
        }

        return !$this->existe([
            ['tipo', $tipo],
            ['valor', $busca]
        ]);
    }
}
