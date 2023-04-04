<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioPagamento\PagamentoModel;

trait EntityBuscarTrait
{
    protected function regraPosBuscar()
    {
        $this->contratoSiape = '';
        if (!$this->trabalho_empresa->vazio() && !empty($this->siape) && $this->id_admin_empresa == 19) {
            $this->contratoSiape = $this->trabalho_empresa->numero() . $this->siape . '341201';
        }
        $this->imagem = imagemUsuario();

        if ($this->validarToken) {
            $Pagamento = new PagamentoModel();
            $this->pagamento = $Pagamento->buscarPagamento($this->get('id'));
        }
        $this->Empresa = new EmpresaEntity();
        $this->Empresa->id($this->id_admin_empresa);
    }
}
