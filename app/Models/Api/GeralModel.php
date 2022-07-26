<?php

namespace App\Models\Api;

use ORM\ORM;
use App\Models\Api\Interface\ListarInterface;

abstract class GeralModel extends ORM implements ListarInterface
{
    protected int $idEmpresa;
    protected ?int $idUsuario = null;

    public function __construct()
    {
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no Model');
        }

        parent::__construct();
        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->idUsuario = array_key_exists('usuario', TOKEN) && is_object(TOKEN['usuario']) ?
            TOKEN['usuario']->get('id') : null;
    }

    protected function montarRetorno(array $dado): array
    {
        return $dado;
    }

    protected function pegarPagina(): int
    {
        $pagina = $this->request->pagina;
        return is_string($pagina) && preg_match('/^[1-9]{1}[0-9]*$/', $pagina) ? $pagina : 1;
    }
    protected function pegarQuantidade(): int
    {
        $quantidade = $this->request->quantidade;
        return is_string($quantidade) && preg_match('/^[1-9]{1}[0-9]*$/', $quantidade) ? $quantidade : 50;
    }

    protected function pegarOrder(?string $ordem = null): string
    {
        return '`id` DESC';
    }

    protected function pegarWhere(): array
    {
        return [['id_admin_empresa', $this->idEmpresa]];
    }
}
