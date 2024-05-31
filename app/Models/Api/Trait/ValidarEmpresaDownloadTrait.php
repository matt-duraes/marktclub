<?php

namespace App\Models\Api\Trait;

use Throwable;
use Erro\Excecao;
use Http\Request;
use Helpers\OrmHelper;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

trait ValidarEmpresaDownloadTrait
{
    private string $nomeCampoEmpresa;
    private int $idEmpresa;
    private int $idUsuario;
    private $whereEmpresa;

    /**
     * Monta o Where para a empresa padrão
     *
     * @param string $usuario      Uuid do usuário que solicitou o download
     * @param string $campoEmpresa
     */
    private function validarEmpresa(string $usuario, string $campoEmpresa = 'id_admin_empresa'): void
    {
        $this->setarIdEmpresa($usuario);
        $this->setarIdUsuario($usuario);
        $this->setaPropriedadeInicial($campoEmpresa);
        $this->setarValoresReais();
    }

    /**
     * Seta o id da empresa pelo uuid do usuário
     *
     * @param string $usuario Uuid do usuário
     */
    private function setarIdEmpresa(string $usuario): void
    {
        $this->idEmpresa = (new OrmHelper(TABELA_USUARIO_EQUIPE))
            ->pegarCampoPor(
                campo: 'id_admin_empresa',
                where: ['uuid', $usuario],
                padrao: 0
            );
    }

    /**
     * Seta o id do usuário pelo uuid do usuário
     *
     * @param string $usuario Uuid do usuário
     */
    private function setarIdUsuario(string $usuario): void
    {
        $this->idUsuario = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($usuario);
    }

    /**
     * @param string $campoEmpresa
     */
    private function setaPropriedadeInicial(string $campoEmpresa): void
    {
        $this->nomeCampoEmpresa = in_array($campoEmpresa, ['empresa', 'id_admin_empresa'])
            ? $campoEmpresa
            : 'id_admin_empresa';

        $this->whereEmpresa = $this->idEmpresa;
        $this->ormWherePadrao = [$this->nomeCampoEmpresa, $this->idEmpresa];
    }

    /**
     * @throws Excecao
     */
    private function setarValoresReais(): void
    {
        if ($this->idEmpresa != 1 || empty($this->idUsuario)) {
            return;
        }

        $empresaNova = '';
        if (
            $this->pExiste('request') &&
            ($this->request instanceof Request) &&
            $this->request->existe('empresa') &&
            !$this->request->vazio('empresa')
        ) {
            $empresaNova = $this->request->empresa;
        } elseif (
            $this->pExiste('empresa') && !empty($this->empresa)
        ) {
            $empresaNova = $this->empresa;
        }

        if (empty($empresaNova)) {
            return;
        }

        try {
            $Empresa = new EmpresaEntity();
            $Empresa->uuid($empresaNova);
            $this->whereEmpresa = $Empresa->get('id');
        } catch (Throwable $e) {
            mensagemErro(
                'Empresa inválida!',
                'Não foi encontrado uma empresa pelo código enviado.',
                error: $e
            );
        }
        $this->ormWherePadrao = [[$this->nomeCampoEmpresa, $this->whereEmpresa]];
    }

    /**
     * Pega o Where padrão para as buscas concatenando com o where da empresa
     *
     * @param array $where Where que deseja colocar padrão
     */
    private function setarWherePadrao(array $where = []): void
    {
        $this->ormWherePadrao = $where;
    }
}
