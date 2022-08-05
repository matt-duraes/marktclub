<?php

namespace App\Models\Api\PontoCvs;

use stdClass;
use Http\Request;
use App\Models\Api\GeralModel;
use App\Classes\PontoCvs\Ordem;
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class PontoModel extends GeralModel
{
    protected string $_tabela = TABELA_PONTO_CVS;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
    }
    public function listarDados(): stdClass
    {
        $dado = $this->campo(['uuid', 'ponto_solicitado', 'data_solicitacao', 'data_voucher', 'voucher'])->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order(new Ordem($this->request->ordem))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'voucher' => strNull($r->voucher)
            ];
        }
        return $retorno;
    }

    protected function pegarWhere(): array
    {
        $where = [];

        if (!empty($this->request->usuario)) {
            $where[] = ['id_usuario_cliente', $this->buscarIdUsuarioPeloCod()];
        }

        return $where;
    }

    private function buscarIdUsuarioPeloCod()
    {
        $Usuario = new ClienteEntity();
        $Usuario->buscar([
            ['cod', $this->request->usuario],
            ['empresa', $this->idEmpresa],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);
        return $Usuario->get('id');
    }

    private function validarRequest()
    {
        $pagina = $this->request->pagina;
        $ordem = new Ordem($this->request->ordem);
        $usuario = $this->request->usuario;

        if (!validarPagina($pagina)) {
            //
        } else if (!$ordem->vazio() && !$ordem->valido()) {
            //
        } else if (validarUuid($usuario)) {
            //
        }
    }
}
