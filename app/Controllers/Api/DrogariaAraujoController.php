<?php

namespace App\Controllers\Api;

use App\Classes\UsuarioCliente\TipoUsuario;
use App\Helpers\DrogariaAraujoHelper;
use App\Models\Api\UsuarioCliente\ClienteModel;
use Controller\Controller;
use Erro\Excecao;
use Helpers\OrmHelper;
use Http\Response;
use Modules\Data;
use Modules\Genero;
use System\Interface\ControllerBuscarInterface;

class DrogariaAraujoController extends Controller implements
    ControllerBuscarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $DrogariaAraujoHelper = new DrogariaAraujoHelper();
        $Response = new Response();
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $Cliente = $OrmHelper->pegarUltimoRegistro(
            [
                ['documento', $id],
                ['status', 1]
            ],
            [
                'id', 'cpf', 'matricula', 'nome',
                'tipo', 'sexo', 'data_nascimento'
            ],
            'object'
        );

        if (empty($Cliente->id)) {
            return $Response->json([
                'existente' => false,
                'mensagem'  => 'Beneficiário não existe na nossa base de dados',
                'vida'      => null
            ]);
        }

        $dependentes = (new ClienteModel(validarEmpresa: false))
            ->buscarDependentesUsuario($Cliente->id);

        $vida = [
            'cpf'                    => $Cliente->cpf,
            'matricula'              => $Cliente->cpf,
            'nome'                   => $Cliente->nome,
            'tipo'                   => ucfirst((new TipoUsuario($Cliente->tipo))->indice()),
            'sexo'                   => ucfirst((new Genero($Cliente->sexo))->genero()),
            'dataNascimento'         => (new Data($Cliente->data_nascimento))->date(),
            'saldoFinanciamento'     => ClienteModel::FINANCIAMENTO_SALDO,
            'limiteFinanciamento'    => ClienteModel::FINANCIAMENTO_LIMITE,
            'grupoCronicoDependente' => 'Nao',
            'cartao'                 => [
                'nome'   => $Cliente->nome,
                'numero' => $Cliente->cpf
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
