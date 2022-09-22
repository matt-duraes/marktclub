<?php

namespace App\Models\Api\EmailAutomatico;

use ORM\Entity;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;

final class ParceiroModel
{
    protected string $_tabela = TABELA_EMAIL_AUTOMATICO;

    private EmpresaEntity $Empresa;
    private ClienteEntity $Usuario;

    public function __construct(
        private ParceiroEntity $Parceiro
    ) {
        $this->Empresa = TOKEN['empresa'];
        // $this->Usuario = TOKEN['usuario'];

        $this->salvarSeNaoExiste();
    }

    private function salvarSeNaoExiste()
    {
        $Email = new EmailEntity();
        try {
            $Email->buscar([
                ['id_usuario_cliente', $this->Usuario->get('id')],
                ['tipo', 1]
            ]);
            if ($Email->status == 'aguardando') {
                $this->adicionarNovoParceiroAoEmail($Email);
            }
        } catch (\Throwable $e) {
            $this->salvarNovoEmail();
        }
    }

    private function adicionarNovoParceiroAoEmail(EmailEntity $Email)
    {
    }
    private function salvarNovoEmail()
    {
    }
}
