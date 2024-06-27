<?php

namespace App\Models\Api\UsuarioEquipe;

use ORM\ORM;
use Http\Request;
use App\Classes\UsuarioEquipe\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class SelectModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_USUARIO_EQUIPE;
    private int $idEmpresa;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        $this->setarIdEmpresa();
    }

    public function listarSelect(): array
    {
        $dado = $this
        ->campo([
            'uuid', 'nome_real'
        ])
        ->where($this->pegarWhere(), obrigatorio: false)
        ->order('nome_real', 'ASC')
        ->read();

        return $this->montarRetornoSelect($dado);
    }

    private function montarRetornoSelect($dado): array
    {
        $retorno = [];
        if (!empty($this->request->titulo)) {
            $retorno[''] = $this->request->titulo;
        }

        foreach ($dado as $r) {
            $retorno[$r->uuid] = $r->nome_real;
        }
        return $retorno;
    }

    public function listarPerfil(): array
    {
        $dado = $this
            ->campo([
                'uuid', 'nome_real', 'nome_perfil', 'imagem_tipo', 'imagem_facebook', 'imagem_google', 'imagem_arquivo'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order('nome_real', 'ASC')
            ->read();

        return $this->montarRetornoPerfil($dado);
    }

    private function montarRetornoPerfil($dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'     => $r->uuid,
                'nome'   => $r->nome_real,
                'perfil' => $r->nome_perfil,
                'imagem' => imagemUsuario($r->imagem_tipo, $r->imagem_arquivo, $r->imagem_facebook, $r->imagem_google)
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): array
    {
        $where = [
            ['id_admin_empresa', $this->idEmpresa]
        ];
        if (defined('TOKEN') && TOKEN['empresa']->id == 1) {
            $where = [[
                'OR',
                ['id_admin_empresa', $this->idEmpresa],
                ['marktclub', 1]
            ]];
        }
        if (chaveExiste('usuario->id_admin_subempresa', TOKEN, true)) {
            $where[] = ['id_admin_subempresa', TOKEN['usuario']->id_admin_subempresa];
        }
        $Tipo = new Tipo($this->request instanceof Request ? $this->request->tipo : null);
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }
        return $where;
    }
}
