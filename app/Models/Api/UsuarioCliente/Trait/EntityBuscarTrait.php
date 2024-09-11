<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioPagamento\PagamentoModel;
use Helpers\OrmHelper;
use Modules\Botao;

trait EntityBuscarTrait
{
    protected function regraPosBuscar()
    {
        $this->contratoSiape = '';
        if (!empty($this->trabalho_empresa) && !empty($this->siape) && $this->id_admin_empresa == 19) {
            $this->contratoSiape = $this->trabalho_empresa . $this->siape . '341201';
        }

        if (!empty($this->trabalho_empresa) && (new TrabalhoEmpresa($this->trabalho_empresa))->valido()) {
            $this->trabalho_empresa = (new TrabalhoEmpresa($this->trabalho_empresa))->indice();
        }

        if ($this->imagem_arquivo) {
            $this->imagem = arquivoPublico('usuario_cliente', $this->imagem_arquivo);
        }

        if ($this->validarToken) {
            $Pagamento = new PagamentoModel();
            $this->pagamento = $Pagamento->buscarPagamento($this->get('id'));
        }
        $this->Empresa = new EmpresaEntity();
        $this->Empresa->id($this->id_admin_empresa);

        if (!empty($this->grupo)) {
            $this->grupo = strCaixaBaixa($this->grupo);
        }

        $this->setarUuidEmpresaPeloId('id_admin_subempresa');

        $dataTermo = $this->data_termo->date();
        $this->termo = new Botao(!empty($dataTermo) && $dataTermo < '2000-01-01' ? 'sim' : 'nao');
    }

    private function setarUuidEmpresaPeloId($campo)
    {
        $id = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarUuidPeloId($this->$campo);
        if ($campo == 'id_admin_subempresa') {
            $this->subempresa = $id;
            return;
        }
        $this->subempresa = $id;
    }
}
