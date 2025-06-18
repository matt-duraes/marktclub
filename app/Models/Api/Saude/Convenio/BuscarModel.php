<?php

namespace App\Models\Api\Saude\Convenio;

use stdClass;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use App\Models\Api\Auth\Token\TokenHelper;

final class BuscarModel extends AbstractOrm
{
    public array        $retorno = [];
    private stdClass    $busca;
    private bool        $clube;
    private TokenHelper $TokenHelper;

    public function __construct(
        string $id
    ) {
        parent::__construct();
        if (empty($id)) {
            mensagemStatus(404);
        }
        $this->TokenHelper = new TokenHelper();
        $this->clube = $this->TokenHelper->eClube();
        $this->buscarConvenio($id);
        $this->montarRetorno();
    }

    private function montarRetorno(): void
    {
        $busca = $this->busca;
        $this->retorno = [
            'id'              => $busca->uuid,
            'titulo'          => $busca->titulo,
            'arquivo_imagem'  => $busca->arquivo_imagem,
            'url'             => $busca->url,
            'status'          => (new Status())->indice($busca->status),
            'empresa'         => (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->mudarListaIdParaUuid(
                jsonDecode($busca->id_admin_empresa, true, true)
            ),
            'endereco_estado' => jsonDecode($busca->endereco_estado, true, true),
        ];
    }

    private function buscarConvenio(string $id): void
    {
        $dado = $this
            ->where($this->montarWhere($id))
            ->campo(
                [
                    'uuid', 'titulo', 'arquivo_imagem', 'url', 'status', 'id_admin_empresa', 'endereco_estado',
                ]
            )
            ->primeiro();

        if (!validarIndiceExiste($dado, 'uuid')) {
            mensagemStatus(404, localhost: 'Não foi encontrado convênio pelo ID informado.');
        }

        $this->busca = $dado;
    }

    private function montarWhere(string $id): array
    {
        $where = [
            [validarUuid($id, false) ? 'uuid' : 'url', $id],
        ];

        if ($this->clube) {
            $where[] = [
                ['status', 1],
                ['id_admin_empresa', 'json', $this->TokenHelper->pegarEmpresa()],
            ];
        }
        return $where;
    }
}
