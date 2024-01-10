<?php

namespace App\Models\Api\Trait;

use Throwable;
use Erro\Excecao;
use Http\Request;
use Helpers\OrmHelper;

trait ValidarEmpresaTrait
{
    private string $nomeCampoEmpresa;
    private bool $campoEmpresaJson = false;
    private ?int $whereEmpresa = null;
    private ?int $idUsuario = null;
    private int $idEmpresa;
    private int $idSubempresa;

    /**
     * Mudar o ID da empresa se tiver pemissão
     *
     * @param int $id ID da empresa
     */
    public function setarIdEmpresaManual(int $id): void
    {
        if (!$this->verificarSePodeMudarEmpresa()) {
            return;
        }
        $this->whereEmpresa = $id;
        $this->idEmpresa = $id;
        $where = [$this->nomeCampoEmpresa, $id];
        if ($this->campoEmpresaJson) {
            [$this->nomeCampoEmpresa, 'json', $id];
        }
        $this->ormWherePadrao = $where;
    }

    /**
     * @return bool
     */
    private function verificarSePodeMudarEmpresa(): bool
    {
        $scope = defined('TOKEN_SCOPE') ? explode(':', TOKEN_SCOPE)[0] ?? '' : '';
        $usuarioPermissao = TOKEN['usuario']->permissao ?? [];
        $usuarioIdEmpresa = TOKEN['usuario']->id_admin_empresa ?? 0;
        return
            !empty($this->idUsuario)
            && !empty($scope)
            && !empty($usuarioPermissao)
            && in_array($scope . '_empresa', $usuarioPermissao)
            && !empty($usuarioIdEmpresa)
            && $usuarioIdEmpresa == 1;
    }

    /**
     * Faz a validação para pegar apenas registros da empresa ou todas se for Markt Club e o usuário tenha permissão
     *
     * @param string $campoEmpresa Se o campo da empresa é o id_admin_empresa ou empresa
     *
     * @throws Excecao
     */
    public function validarEmpresa(string $campoEmpresa = 'id_admin_empresa', bool $json = false): void
    {
        $this->campoEmpresaJson = $json;
        $this->setarIdEmpresa();
        $this->setarIdUsuario();
        $this->setaPropriedadeInicial($campoEmpresa);
        $this->setarValoresReais();
        $this->setarWherePadrao();
    }

    private function validarSubempresa(bool $json = false)
    {
        if (!defined('TOKEN') || !is_array(TOKEN) || !array_key_exists('usuario', TOKEN)) {
            return;
        }
        $id = TOKEN['usuario']->id_admin_subempresa ?? 0;
        $this->idSubempresa = $id;
        if (empty($id)) {
            return;
        }
        $where = $this->ormWherePadrao;
        $whereSubempresa = [['id_admin_subempresa', $id]];
        if ($json) {
            $whereSubempresa = [['id_admin_subempresa', 'json', $id]];
        }

        if (empty($where)) {
            $this->ormWherePadrao = $whereSubempresa;
            return;
        }
        $this->ormWherePadrao = array_merge($where, $whereSubempresa);
    }

    /**
     * Verifica se existe token e seta a empresa
     *
     * @throws Excecao
     */
    private function setarIdEmpresa(): void
    {
        $this->verificarSeExisteToken();
        $this->idEmpresa = TOKEN['empresa']->id;
    }

    /**
     * @throws Excecao
     */
    private function verificarSeExisteToken(): void
    {
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no Model.');
        }
    }

    /**
     * @throws Excecao
     */
    private function setarIdUsuario(): void
    {
        $this->verificarSeExisteToken();
        $this->idUsuario = array_key_exists('usuario', TOKEN) && !vazio(TOKEN['usuario'])
            ? TOKEN['usuario']->id
            : null;
    }

    /**
     * @param string $campoEmpresa
     *
     */
    private function setaPropriedadeInicial(string $campoEmpresa): void
    {
        $this->nomeCampoEmpresa = in_array($campoEmpresa, ['empresa', 'id_admin_empresa'])
            ? $campoEmpresa
            : 'id_admin_empresa';

        $this->whereEmpresa = $this->idEmpresa;
        $where = [$this->nomeCampoEmpresa, $this->idEmpresa];
        if ($this->campoEmpresaJson) {
            $where = [$this->nomeCampoEmpresa, 'json', $this->idEmpresa];
        }
        $this->ormWherePadrao = $where;
    }

    /**
     * @throws Excecao
     */
    private function setarValoresReais(): void
    {
        if ($this->idEmpresa != 1 || empty($this->idUsuario)) {
            return;
        }
        if (!$this->verificarSePodeMudarEmpresa()) {
            return;
        }

        if (
            (!property_exists($this, 'request')
            || !($this->request instanceof Request)
            || !$this->request->existe('empresa')
            || $this->request->vazio('empresa')) &&
            (
                !$this->propriedadeExiste('empresa')
            )
        ) {
            $this->whereEmpresa = null;
            $this->ormWherePadrao = [];
            return;
        }

        $idEmpresa = $this->propriedadeExiste('empresa') ? $this->empresa : $this->request->empresa;
        try {
            $Empresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
            $this->whereEmpresa = $Empresa->pegarIdPeloUuid($idEmpresa);
        } catch (Throwable $e) {
            mensagemErro('Empresa inválida!', 'Não foi encontrado uma empresa pelo código enviado.', error: $e);
        }
    }

    /**
     * Pega o Where padrão para as buscas concatenando com o where da empresa
     *
     * @param array $where Where que deseja colocar padrão
     */
    private function setarWherePadrao(array $where = []): void
    {
        if (!empty($whereEmpresa)) {
            $this->idEmpresa = $this->whereEmpresa;
        }
        $wherePadrao = [[$this->nomeCampoEmpresa, $this->whereEmpresa]];
        if ($this->campoEmpresaJson) {
            $wherePadrao = [[$this->nomeCampoEmpresa, 'json', $this->whereEmpresa]];
        }
        if (!empty($where) && !empty($this->ormWherePadrao)) {
            $this->ormWherePadrao = array_merge([$where], $wherePadrao);
        } elseif (!empty($where)) {
            $this->ormWherePadrao = $where;
        } elseif (!empty($this->whereEmpresa)) {
            $this->ormWherePadrao = $wherePadrao;
        }
    }
}
