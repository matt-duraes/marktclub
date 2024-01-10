<?php

namespace App\Models\Site\Endereco;

use Helpers\ListaHelper;
use App\Helpers\ClubeApiHelper;
use System\Classes\Endereco\Tipo;
use System\Classes\Endereco\Local;

final class ListaModel extends ClubeApiHelper
{
    public array $endereco = [];
    public array $principal = [];
    public array $pais = [];
    public array $estado = [];
    public array $cidade = [];
    public int $quantidade = 0;
    public bool $existe = false;
    private array $busca = [];

    public function __construct(
        private string $id,
        private Tipo $tipo,
        private Local $local
    ) {
        // sleep(3);
        parent::__construct();
        $this->buscarDado();
        $this->montarEndereco();
        $this->enderecoPrincipal();
    }

    private function buscarDado()
    {
        $this->busca = $this
            ->validar(login: true)
            ->json([
                'local'   => $this->local->indice(),
                'tipo'    => $this->tipo->indice(),
                'vinculo' => $this->id
            ])
            ->get('/endereco')
            ->object()->dado ?? [];
    }

    private function montarEndereco()
    {
        $retorno = [];
        $estadoLista = (new ListaHelper())->add('outro', 'Outros')->estado()->r();
        $paisLista = (new ListaHelper())->add('outro', 'Outros')->pais()->r();
        foreach ($this->busca as $r) {
            if (empty($r->latitude) || empty($r->longitude)) {
                continue;
            }

            $estado = !empty($r->estado) ? $estadoLista[$r->estado] : 'outro';
            $cidade = !empty($r->cidade) ? $r->cidade : 'outro';

            $pais = $r->pais;
            if (empty($pais) && array_key_exists($estado, $estadoLista)) {
                $pais = 'BR';
            }
            $pais = empty($pais) || !array_key_exists($pais, $paisLista) ? 'outro' : $pais;

            if (!array_key_exists($r->pais, $retorno)) {
                $retorno[$pais] = [];
                $this->pais[$pais] = $paisLista[$pais];
            }
            if (!array_key_exists($estado, $retorno[$pais])) {
                $retorno[$pais][$estado] = [];
                $this->estado[$estado] = $estado;
            }
            if (!array_key_exists($cidade, $retorno[$pais][$estado])) {
                $retorno[$pais][$estado][$cidade] = [];
                $this->cidade[$cidade] = $cidade;
            }

            $retorno[$pais][$estado][$cidade][] = $this->setarEndereco($r);
        }
        $this->endereco = $retorno;
    }

    private function setarEndereco($r)
    {
        $this->quantidade++;
        return [
            'id'        => $r->id,
            'endereco'  => $r->titulo . ' - ' . $r->completo,
            'latitude'  => $r->latitude,
            'longitude' => $r->longitude,
            'telefone'  => $r->telefone,
            'link'      => 'https://www.google.com.br/maps/dir//' . $r->latitude . ',%20' . $r->longitude
        ];
    }

    private function enderecoPrincipal()
    {
        if (empty($this->endereco)) {
            return;
        }
        $item = $this->endereco;
        for ($i = 0; $i < 4; $i++) {
            $item = reset($item);
            if (array_key_exists('id', $item)) {
                $this->existe = true;
                $this->principal = $item;
                break;
            }
        }
    }
}
