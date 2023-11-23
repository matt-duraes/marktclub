<?php

namespace App\Models\Api\BannerLogin;

use Helpers\OrmHelper;
use ORM\Entity;

class BannerEntity extends Entity
{
    protected string $ormTabela = TABELA_BANNER_LOGIN;
    public string $titulo;
    public string $id;
    public array $id_admin_empresa;
    public string $url_1;
    public string $url_2;
    public string $url_3;
    public int $padrao;
    public array $empresa;
    protected array $ormBuscar = [
        'titulo', 'url_1', 'url_2', 'url_3', 'id_admin_empresa', 'padrao'
    ];
    protected array $ormSalvar = [
        'titulo', 'url_1', 'url_2', 'url_3', 'id_admin_empresa', 'padrao'
    ];

    public function regraPosBuscar()
    {
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $this->empresa = $ormHelper->mudarListaIdParaUuid($this->id_admin_empresa);
    }

    public function regraSalvar()
    {
        $this->validarDados();
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $this->id_admin_empresa = $ormHelper->mudarListaUuidParaId($this->empresa);
        $this->validarEmpresaNaoEncontrada();
        $this->validarSeJaExiste();
    }

    private function validarDados()
    {
        if (empty($this->empresa) || !is_array($this->empresa)) {
            return mensagemErro(
                'Campo obrigatório!',
                'O campo empresa não pode ser vazio ou o formato está errado.'
            );
        }
    }

    private function validarEmpresaNaoEncontrada()
    {
        if (empty($this->id_admin_empresa)) {
            return mensagemErro(
                'Empresa inválida',
                'Uma ou mais empresas informadas não são válidas.'
            );
        }
    }

    private function validarSeJaExiste()
    {
        $ormEmpresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $id = $ormEmpresa->mudarListaUuidParaId($this->empresa);

        $ormBanner = new OrmHelper(TABELA_BANNER_LOGIN);
        $dado = $ormBanner
            ->where([
                ['id_admin_empresa', 'json', jsonEncode($id)]
            ])
            ->read();

        $id = $this->id ?? null;
        if (!empty($dado) && $dado[0]->uuid !== $id) {
            return mensagemErro(
                'Banner já cadastrado',
                'Já existe um banner cadastrado para uma ou mais empresas informadas.',
                400
            );
        }
    }
}
