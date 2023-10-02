<?php

namespace App\Controllers\Api;

use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\UsuarioCliente\ClienteModel;
use Controller\Controller;
use Erro\Erro;
use Erro\Excecao;
use Http\Response;
use System\Interface\ControllerBuscarInterface;

class DrogariaAraujoController extends Controller implements
    ControllerBuscarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao|Erro
     */
    public function getBuscar(string $id): Response
    {
        $Response = new Response();
        $ClienteEntity = new ClienteEntity();
        $ClienteEntity->buscar([
            ['documento', $id]
        ], false);

        if (empty($ClienteEntity->id)) {
            return $Response->json([
                'existente' => false,
                'mensagem'  => 'Beneficiário não existe na nossa base de dados',
                'vida'      => null
            ]);
        }

        $dependentes = (new ClienteModel())->buscarDependentesUsuario($ClienteEntity->getId());

        $vida = [
            'cpf'                    => $ClienteEntity->cpf->numero(),
            'matricula'              => $ClienteEntity->matricula,
            'nome'                   => $ClienteEntity->nome->nome(),
            'tipo'                   => $ClienteEntity->tipo->indice(),
            'sexo'                   => $ClienteEntity->genero->genero(),
            'dataNascimento'         => $ClienteEntity->data_nascimento->date(),
            'saldoFinanciamento'     => ClienteModel::FINANCIAMENTO_SALDO,
            'limiteFinanciamento'    => ClienteModel::FINANCIAMENTO_LIMITE,
            'grupoCronicoDependente' => 'nao',
            'cartao'                 => [
                'nome'   => $ClienteEntity->nome->nome(),
                'numero' => null
            ],
            'dependentes'            => $dependentes,
            'codigoPlano'            => $ClienteEntity->codigo_plano
        ];

        return $Response->json([
            'existente' => true,
            'mensagem'  => null,
            'vida'      => $vida
        ]);
    }
}
