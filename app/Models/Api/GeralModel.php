<?php

namespace App\Models\Api;

use ORM\ORM;
use stdClass;
use Http\Request;
use System\Interface\ModelListarInterface;
use App\Models\Api\AdminEmpresa\EmpresaEntity;

abstract class GeralModel extends ORM implements ModelListarInterface
{
    protected int $idEmpresa;
    protected ?int $whereEmpresa = null;
    protected ?int $idUsuario = null;
    protected ?Request $request = null;

    public function __construct()
    {
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no Model.');
        }
        parent::__construct();

        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->whereEmpresa = $this->idEmpresa;
        $this->idUsuario = array_key_exists('usuario', TOKEN) && is_object(TOKEN['usuario']) ?
            TOKEN['usuario']->get('id') : null;
        $this->validarEmpresa();
    }

    private function validarEmpresa()
    {
        if ($this->idEmpresa != 1) {
            return;
        }

        $scope = explode(':', TOKEN_SCOPE)[0] ?? '';
        $usuarioPermissao = TOKEN['usuario']->permissao;
        if (
            empty($this->idUsuario) ||
            !($this->request instanceof Request) ||
            !$this->request->existe('empresa') ||
            !in_array($scope . '_empresa', $usuarioPermissao)
        ) {
            return;
        }

        if ($this->request->vazio('empresa')) {
            $this->whereEmpresa = null;
            return;
        }

        try {
            $Empresa = new EmpresaEntity();
            $Empresa->id($this->request->empresa);
            $this->whereEmpresa = $Empresa->get('id');
        } catch (\Throwable $e) {
            mensagemErro('Empresa inválida!', 'Não foi encontrado uma empresa pelo código enviado.', error: $e);
        }
    }

    public function listarDados(): stdClass
    {
        return object([]);
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
