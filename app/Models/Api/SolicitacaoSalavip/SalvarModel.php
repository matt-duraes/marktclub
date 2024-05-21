<?php

namespace App\Models\Api\SolicitacaoSalavip;

use Modules\Cpf;
use Modules\Nome;
use Modules\Email;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\SolicitacaoVoucher\VoucherEntity;
use App\Models\Api\UsuarioCliente\SalvarAtualizarModel;

final class SalvarModel
{
    private int $idEmpresa;
    private string $idUsuario;
    public VoucherEntity $Voucher;

    public function __construct(
        private Nome $nome,
        private Cpf $cpf,
        private Email $email_pessoal
    ) {
        $this->idEmpresa = TOKEN['empresa']->id;
        $this->verificarSeEmpresaAutorizado();
        $this->verificarDadosUsuario();
        $this->buscarUsuario();
        $this->salvarVoucher();
    }

    private function verificarSeEmpresaAutorizado()
    {
        if (!in_array($this->idEmpresa, [1, 2])) {
            mensagemStatus(401);
        }
    }

    private function verificarDadosUsuario()
    {
        if (!$this->nome->valido()) {
            mensagemErroValido('Nome');
        } elseif ($this->cpf->vazio()) {
            mensagemErroVazio('CPF');
        } elseif (!$this->cpf->valido()) {
            mensagemErroValido('CPF');
        } elseif ($this->email_pessoal->vazio()) {
            mensagemErroVazio('E-mail');
        } elseif (!$this->email_pessoal->valido()) {
            mensagemErroValido('E-mail');
        }
    }

    private function buscarUsuario()
    {
        $Usuario = new SalvarAtualizarModel(
            empresa: $this->idEmpresa
        );
        $Usuario->nome = $this->nome;
        $Usuario->email_pessoal = $this->email_pessoal;
        $Usuario->cpf = $this->cpf;
        $Usuario->buscar();
        $this->idUsuario = $Usuario->id;
    }

    private function salvarVoucher()
    {
        $Cliente = new ClienteEntity();
        $Cliente->id($this->idUsuario);

        $Parceiro = new LojaEntity();
        $Parceiro->id(2);

        $this->Voucher = new VoucherEntity(
            Parceiro: $Parceiro,
            Usuario: $Cliente,
            tipo: new Tipo(Tipo::LOJA)
        );
        $this->Voucher->salvar();
    }
}
