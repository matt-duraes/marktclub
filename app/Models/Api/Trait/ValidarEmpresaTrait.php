<?php

namespace App\Models\Api\Trait;

use Http\Request;
use App\Models\Api\AdminEmpresa\EmpresaEntity;

trait ValidarEmpresaTrait
{
    private string $_campoEmpresa;

    /**
     * Verifica se existe token e seta a empresa
     *
     * @return void
     * @throws Excecao  Retorna uma Excecao caso não exista token
     */
    private function setarIdEmpresa(): void
    {
        $this->_verificaSeExisteToken();
        $this->idEmpresa = TOKEN['empresa']->get('id');
    }
    /**
     * Faz a validação para pegar apenas registros da empresa ou todas se for Markt Club e o usuário tenha permissão
     *
     * @param   string  $campoEmpresa   Se o campo da empresa é o id_admin_empresa ou empresa
     * @return void
     * @throws Excecao  Retorna uma Excecao caso não exista token
     */
    private function validarEmpresa(string $campoEmpresa = 'id_admin_empresa'): void
    {
        $this->setarIdEmpresa();
        $this->_setaPropriedadeInicial($campoEmpresa);
        $this->_setarValoresReais();
        $this->setarWherePadrao();
    }

    private function _verificaSeExisteToken()
    {
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no Model.');
        }
    }
    private function _setaPropriedadeInicial(string $campoEmpresa)
    {
        $this->_campoEmpresa = in_array($campoEmpresa, ['empresa', 'id_admin_empresa']) ? $campoEmpresa : 'id_admin_empresa';
        $this->whereEmpresa = $this->idEmpresa;
        $this->_wherePadrao = [$campoEmpresa => $this->idEmpresa];
        $this->idUsuario = array_key_exists('usuario', TOKEN) && is_object(TOKEN['usuario']) ?
            TOKEN['usuario']->get('id') : null;
    }

    private function _setarValoresReais()
    {
        if ($this->idEmpresa != 1 || empty($this->idUsuario)) {
            return;
        }

        $scope = explode(':', TOKEN_SCOPE)[0] ?? '';
        $usuarioPermissao = TOKEN['usuario']->permissao;
        if (
            empty($this->idUsuario) ||
            !in_array($scope . '_empresa', $usuarioPermissao)
        ) {
            return;
        }

        if (
            !property_exists($this, 'request') ||
            !($this->request instanceof Request) ||
            !$this->request->existe('empresa') ||
            $this->request->vazio('empresa')
        ) {
            $this->whereEmpresa = null;
            $this->_wherePadrao = [];
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

    /**
     * Pega o Where padrão para as buscas concatenando com o where da empresa
     *
     * @param   array $where    Where que deseja colocar padrão
     */
    private function setarWherePadrao(array $where = []): void
    {
        if (empty($where)) {
            return;
        } else if (!empty($this->_wherePadrao)) {
            $this->_wherePadrao = array_merge([$where], [[$this->_campoEmpresa, $this->whereEmpresa]]);
            return;
        }
        $this->_wherePadrao = $where;
    }
}
