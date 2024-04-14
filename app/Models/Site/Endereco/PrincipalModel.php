<?php

namespace App\Models\Site\Endereco;

use stdClass;
use App\Helpers\ClubeApiHelper;

final class PrincipalModel extends ClubeApiHelper
{
    use LocalTrait;
    use RetornoTrait;

    public array $endereco = [];
    private stdClass $busca;

    public function __construct(
        private string $id,
        private string $local,
        private string $latitude,
        private string $longitude
    ) {
        parent::__construct();
        $this->buscarDado();

        if (!vazio($this->busca)) {
            $this->endereco = $this->montarRetorno([$this->busca])[0] ?? [];
        }
    }

    private function buscarDado()
    {
        $this->busca = $this
            ->validar(login: true)
            ->json([
                'local_principal'  => $this->pegarLocal($this->local),
                'local_secundario' => 'clube',
                'vinculo'          => $this->id,
                'latitude'         => $this->latitude,
                'longitude'        => $this->longitude
            ])
            ->get('/endereco/principal')
            ->object()->dado ?? (object)[];
    }
}
