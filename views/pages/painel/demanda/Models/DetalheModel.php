<?php

namespace Painel\Demanda\Models;

use stdClass;
use Helpers\ApiHelper;
use PainelModel\Perfil\Equipe;
use PainelModel\Perfil\Empresa;
use App\Classes\DemandaDado\Status;

final class DetalheModel
{
    private stdClass $demanda;

    public function __construct($id)
    {
        $this->demanda = (new ApiHelper(token: true))
            ->validar('Página não encontrada!', status: 404, login: true)
            ->get('/demanda-dado/' . $id)
            ->object()->dado ?? (object)[];
    }

    public function montarDado()
    {
        $dado = $this->demanda;
        $Equipe = new Equipe();
        $Empresa = new Empresa();

        $notificar = $dado->seguindo;
        if (!empty($dado->equipe)) {
            $notificar = array_merge($notificar, [$dado->equipe]);
        }
        $dado->dono = $dado->equipe == sessao('USUARIO.id');
        $dado->empresa = $Empresa->unico($dado->empresa);
        $dado->data_criacao = dataBr($dado->data_criacao);
        $dado->data_entrega = !empty($dado->data_entrega) ? dataBr($dado->data_entrega) : '';
        $dado->equipe = $Equipe->unico($dado->equipe);
        $dado->seguindo = $Equipe->lista($notificar);
        $dado->estouSeguindo = in_array(sessao('USUARIO.id'), $notificar);
        $dado->notificar = !empty($notificar) ? array_unique($notificar) : [];
        $dado->liberado = in_array($dado->status, [Status::ANDAMENTO, Status::LIBERADA]) ? 'sim' : 'nao';

        return $dado;
    }
}
