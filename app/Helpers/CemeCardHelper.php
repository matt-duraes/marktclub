<?php

namespace App\Helpers;

use Helpers\CurlHelper;
use Helpers\OrmHelper;
use stdClass;

class CemeCardHelper extends CurlHelper
{
    private int $idCemeCard = 229;

    public function __construct()
    {
        parent::__construct(env('CEMECARD_API_LINK'));
    }

    public function buscarCarteirinha(array $carteirinhas)
    {
        $carteirinha = $this->pegarCarteirinha($carteirinhas);

        if (
            empty(TOKEN['empresa'])
            || TOKEN['empresa']->id != $this->idCemeCard
            || empty(TOKEN['usuario']->id)
            || empty($carteirinha)
        ) {
            return $carteirinhas;
        }

        $usuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))->pegarPrimeiroRegistro(
            ['id', TOKEN['usuario']->id],
            ['documento']
        );
        $dado = $this
            ->get('/users/validaUsuario/' . $usuario['documento'])
            ->array();

        return $this->montarRetornoCarteirinha($carteirinha, $dado);
    }

    private function montarRetornoCarteirinha(stdClass $carterinha, array $dado)
    {
        return [
            (object) [
                'uuid'             => $carterinha->uuid,
                'titulo'           => $carterinha->titulo,
                'empresa_uuid'     => $carterinha->empresa_uuid,
                'bg_frente'        => $carterinha->bg_frente,
                'matricula'        => $carterinha->matricula,
                'data_criacao'     => $carterinha->data_criacao,
                'data_atualizacao' => $carterinha->data_atualizacao,
                'status'           => $carterinha->status,
                'nome'             => $dado['name'] ?? '',
                'cpf'              => $dado['document'] ?? '',
                'data_nascimento'  => $dado['birthdate'] ?? '',
                'estado'           => $dado['contact']['address']['state']['uf'] ?? '',
                'data_validade'    => $dado['plan_venc'] ?? '',
                'qr_code'          => $dado['qrcode_link'] ?? '',
                'cartao_numero'    => $dado['card_number'] ?? '',
            ]
        ];
    }

    private function pegarCarteirinha($carteirinhas)
    {
        foreach ($carteirinhas as $objeto) {
            if ($objeto->empresa_id == $this->idCemeCard) {
                return $objeto;
            }
        }
        return [];
    }
}
