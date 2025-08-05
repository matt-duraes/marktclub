<?php

namespace App\Models\Api\Saude\Simulacao;

use ORM\ORM;
use App\Classes\SaudeSimulacao\Status;

final class ContratarModel extends ORM
{
    protected string $ormTabela = TABELA_SAUDE_SIMULACAO;

    public int $id;
    public bool $proasa = false;

    public function __construct(
        ?string $uuid = null
    )
    {
        parent::__construct();
        $this->buscarRegistro($uuid);
    }

    public function contratado()
    {
        $this
            ->dado([
                'status' => new Status(Status::ENVIADO)
            ])
            ->where(['id', $this->id])
            ->update();
    }

    private function buscarRegistro(?string $uuid)
    {
        if(empty($uuid)) {
            $this->erroPadrao();
        }
        $busca = $this->campo(['id', 'id_saude_convenio'])->where(['uuid', $uuid])->primeiro();
        if(!validarIndiceExiste($busca, 'id')) {
            $this->erroPadrao();
        }
        $this->id = $busca->id;
        $proasa = env('PROASA_CONVENIO_ID', []);
        $this->proasa = in_array($busca->id_saude_convenio, $proasa);
    }


    private function erroPadrao()
    {
        mensagemErro('Erro!', 'Ocorre um erro ao buscar sua simulação, por favor, tente novamente.', status: 400);
    }
}
