<?php

namespace Tests\Api;

use App\Classes\ComercialPopup\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use Tests\Token\Clube;

class ComercialPopupTest extends Clube
{
    private array $idEmpresa = ['14afa776394ada4be23be6acf7e3259e'];
    protected string $scope = 'comercial_popup';
    protected string $uri = '/comercial-popup';
    public string $automatico = 'crud';

    public function __construct()
    {
        $this->tabela(TABELA_COMERCIAL_POPUP)->resetar();
        parent::__construct();
    }

    protected function pegarBody()
    {
        return [
            'titulo_painel' => nomeCompletoAleatorio(),
            'empresa'       => $this->idEmpresa,
            'titulo'        => 'venha conferir a melhor',
            'texto'         => 'Aqui vc tera o mejor do melhor sempre',
            'data_inicio'   => date('d/m/Y'),
            'data_final'    => date('d/m/Y'),
            'usuario_tipo'  => TipoUsuario::TITULAR,
            'status'        => Status::ATIVO
        ];
    }
}
