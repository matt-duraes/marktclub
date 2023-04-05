<?php

namespace App\Models\Api\Trait;

use Http\Request;
use App\Models\Api\UsuarioEquipe\HelperModel;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

trait ValidarEmpresaDownloadTrait
{
    private string $nomeCampoEmpresa;

    /**
     * Mudar o ID da empresa se tiver pemissão
     *
     * @param   int     $id     ID da empresa
     */
    public function setarIdEmpresaManual(int $id)
    {
        if (!$this->verificarSePodeMudarEmpresa()) {
            return;
        }
        $this->whereEmpresa = $id;
        $this->idEmpresa = $id;
        $this->ormWherePadrao = [$this->nomeCampoEmpresa, $id];
    }

    /**
     * Seta o id da empresa pelo uuid do usuário
     *
     * @param  string   $usuario  Uuid do usuário
     */
    private function setarIdEmpresa(string $usuario): void
    {
        $Equipe = new HelperModel();
        $this->idEmpresa = $Equipe->pegarIdDaEmpresaPeloUuid($usuario);
    }

    /**
     * Seta o id do usuário pelo uuid do usuário
     *
     * @param  string  $usuario  Uuid do usuário
     */
    private function setarIdUsuario(string $usuario): void
    {
        $Equipe = new HelperModel();
        $this->idUsuario = $Equipe->pegarIdPeloUuid($usuario);
    }
    /**
     * Faz a validação para pegar apenas registros da empresa ou todas se for Markt Club e o usuário tenha permissão
     *
     * @param   string  $campoEmpresa   Se o campo da empresa é o id_admin_empresa ou empresa
     * @return void
     * @throws Excecao  Retorna uma Excecao caso não exista token
     */
    private function validarEmpresa(string $usuario, string $campoEmpresa = 'id_admin_empresa'): void
    {
        $this->setarIdEmpresa($usuario);
        $this->setarIdUsuario($usuario);
        $this->setaPropriedadeInicial($campoEmpresa);
        $this->setarValoresReais();
        $this->setarWherePadrao();
    }

    private function setaPropriedadeInicial(string $campoEmpresa)
    {
        $this->nomeCampoEmpresa = in_array(
            $campoEmpresa,
            ['empresa', 'id_admin_empresa']
        ) ? $campoEmpresa : 'id_admin_empresa';

        $this->whereEmpresa = $this->idEmpresa;
        $this->ormWherePadrao = [$this->nomeCampoEmpresa, $this->idEmpresa];
    }

    private function setarValoresReais()
    {
        if ($this->idEmpresa != 1 || empty($this->idUsuario)) {
            return;
        }

        if (!$this->verificarSePodeMudarEmpresa()) {
            return;
        }

        if (
            !property_exists($this, 'request') ||
            !($this->request instanceof Request) ||
            !$this->request->existe('empresa') ||
            $this->request->vazio('empresa')
        ) {
            $this->whereEmpresa = null;
            $this->ormWherePadrao = [];
            return;
        }

        try {
            $Empresa = new EmpresaEntity();
            $Empresa->uuid($this->request->empresa);
            $this->whereEmpresa = $Empresa->get('id');
        } catch (\Throwable $e) {
            mensagemErro('Empresa inválida!', 'Não foi encontrado uma empresa pelo código enviado.', error: $e);
        }
    }
    private function verificarSePodeMudarEmpresa(): bool
    {
        $scope = defined('TOKEN_SCOPE') ? explode(':', TOKEN_SCOPE)[0] ?? '' : '';
        $Equipe = new HelperModel();
        $usuarioPermissao = $Equipe->pegarPermissaoPeloId($this->idUsuario);

        return
            !empty($this->idUsuario) &&
            !empty($scope) &&
            !empty($usuarioPermissao) &&
            in_array($scope . '_empresa', $usuarioPermissao);
    }

    /**
     * Pega o Where padrão para as buscas concatenando com o where da empresa
     *
     * @param   array $where    Where que deseja colocar padrão
     */
    private function setarWherePadrao(array $where = []): void
    {
        if (!empty($where) && !empty($this->ormWherePadrao)) {
            $this->ormWherePadrao = array_merge([$where], [[$this->nomeCampoEmpresa, $this->whereEmpresa]]);
        } elseif (!empty($where)) {
            $this->ormWherePadrao = $where;
        } elseif (!empty($this->whereEmpresa)) {
            $this->ormWherePadrao = [[$this->nomeCampoEmpresa, $this->whereEmpresa]];
        }
    }
}
