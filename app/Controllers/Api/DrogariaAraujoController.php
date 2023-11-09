<?php

namespace App\Controllers\Api;

use App\Classes\UsuarioCliente\Status;
use App\Helpers\DrogariaAraujoHelper;
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
        $DrogariaAraujoHelper = new DrogariaAraujoHelper();
        $Response = new Response();
        $ClienteEntity = new ClienteEntity();
        $ClienteEntity->buscar([
            ['documento', $id],
            ['status', (new Status(Status::ATIVO))->numero()]
        ], false);

        if (empty($ClienteEntity->id)) {
            return $Response->json([
                'existente' => false,
                'mensagem'  => 'Beneficiário não existe na nossa base de dados',
                'vida'      => null
            ]);
        }

        $dependentes = (new ClienteModel())
            ->buscarDependentesUsuario($ClienteEntity->getId());

        $vida = [
            'cpf'                    => $ClienteEntity->cpf->numero(),
            'matricula'              => $ClienteEntity->cpf->numero(),
            'nome'                   => $ClienteEntity->nome->nome(),
            'tipo'                   => ucfirst($ClienteEntity->tipo->indice()),
            'sexo'                   => ucfirst($ClienteEntity->genero->genero()),
            'dataNascimento'         => $ClienteEntity->data_nascimento->date(),
            'saldoFinanciamento'     => ClienteModel::FINANCIAMENTO_SALDO,
            'limiteFinanciamento'    => ClienteModel::FINANCIAMENTO_LIMITE,
            'grupoCronicoDependente' => 'Nao',
            'cartao'                 => [
                'nome'   => $ClienteEntity->nome->nome(),
                'numero' => $ClienteEntity->cpf->numero()
            ],
            'dependentes'            => $dependentes,
            'codigoPlano'            => $DrogariaAraujoHelper->getCodigoPlano()
        ];

        return $Response->json([
            'existente' => true,
            'mensagem'  => null,
            'vida'      => $vida
        ]);
    }
}
