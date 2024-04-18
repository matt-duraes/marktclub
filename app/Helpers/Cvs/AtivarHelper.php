<?php

namespace App\Helpers\Cvs;

use stdClass;
use Modules\Cpf;
use App\Classes\UsuarioCliente\Status;
use App\Models\Api\UsuarioCliente\SalvarAtualizarModel;

final class AtivarHelper
{
    private stdClass $busca;
    private string $link;

    public function __construct(
        private Cpf $cpf
    ) {
        $this->link = env('CVS_API_LINK_USUARIO', '');

        $this->validarCpf();
        $this->buscarUsuario();
        $this->validarRetorno();
        $this->salvarUsuarioBanco();
    }

    private function validarCpf()
    {
        if ($this->cpf->vazio()) {
            mensagemErroVazio('CPF');
        } elseif (!$this->cpf->valido()) {
            mensagemErroValido('CPF');
        }
    }

    private function buscarUsuario()
    {
        if (eLocalhost()) {
            $this->busca = (object)[
                'd' => '["retorno:1"]'
            ];
            return;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'xcpf' => (string)str_pad($this->cpf->numero(), 11, '0', STR_PAD_LEFT),
        ]));

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json;charset=utf-8;',
        ]);

        $retorno = curl_exec($ch);

        curl_close($ch);

        $this->busca = jsonDecode($retorno, false);
    }

    private function validarRetorno()
    {
        if (!chaveExiste('d', $this->busca)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao buscar seu usuário, por favor, tente novamente.', 500);
        } elseif ($this->busca->d != '["retorno:1"]') {
            return mensagemErro('Usuário não encontrado!', 'Verifique o CPF informado e tente novamente.', 404);
        }
    }

    private function salvarUsuarioBanco()
    {
        $Usuario = new SalvarAtualizarModel(
            empresa: 198
        );
        $Usuario->cpf = $this->cpf;
        $Usuario->status = new Status(Status::INATIVO);
        $Usuario->buscar();
    }
}
