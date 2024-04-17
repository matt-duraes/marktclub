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
        private Cpf $cpf,
        private array $campo
    ) {
        $this->link = 'https://api-spbancarios.bsys.digital/api/v1/return_clube_vantagem';

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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'xcpf' => $this->cpf->numero(),
        ]));

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json;charset=utf-8;',
        ]);

        $retorno = curl_exec($ch);
        $status_html = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $erro = curl_error($ch);
        pp($status_html);
        pp($erro);
        ppe($retorno);

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

    public function usuario()
    {
    }
}
