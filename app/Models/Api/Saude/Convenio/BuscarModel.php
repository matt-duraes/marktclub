<?php

namespace App\Models\Api\Saude\Convenio;

use ORM\ORM;
use stdClass;

final class BuscarModel extends AbstractOrm
{
    private stdClass $busca;
    public array $retorno = [];

    public function __construct(
        string $id
    )
    {
        parent::__construct();
        if(empty($id)) {
            mensagemStatus(404);
        }
        $this->buscarConvenio($id);
        $this->montarRetorno();
    }

    private function montarRetorno()
    {
        $busca = $this->busca;
        $this->retorno = [
            'id' => $busca->uuid,
            'sequencia' => $busca->sequencia,
            'item' => $busca->item,
            'simulacao' => $busca->simulacao
        ];
    }

    private function buscarConvenio(string $id)
    {
        $dado = $this
            ->where($this->montarWhere($id))
            ->campo(['uuid', 'sequencia', 'item', 'simulacao'])
            ->primeiro();

        if(!validarIndiceExiste($dado, 'uuid')) {
            mensagemStatus(404, localhost: 'Não foi encontrado convênio pelo ID informado.');
            return;
        }

        $this->busca = $dado;
    }

    private function montarWhere(string $id) {
        if(validarUuid($id, false)) {
            return [
                ['status', 1],
                ['uuid', $id]
            ];
        }
        return [
            ['status', 1],
            ['url', $id]
        ];
    }
}
