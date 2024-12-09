<?php

namespace App\Models\Api\Analytics;

use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Http\Request;
use Modules\Botao;
use ORM\ORM;

final class SalvarModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_ANALYTICS;
    private array $dado = [];
    private ?int $idUsuario = null;
    private int $idEmpresa;

    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa();
        $this->montarDado();
        $this->salvar();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function montarDado(): void
    {
        $this->dado = $this->request->lista([
            'vinculo', 'usuario_nome', 'usuario_cpf', 'hash', 'dispositivo',
            'os', 'browser', 'versao', 'tablet', 'ip', 'agent', 'pais', 'cidade',
            'latitude', 'longitude', 'url'
        ]);

        $this->dado['mobile'] = (new Botao($this->request->mobile))->numero();
        $this->dado['tablet'] = (new Botao($this->request->tablet))->numero();
        $this->dado['usuario_tipo'] = (new TipoUsuario($this->request->usuario_tipo))->numero();
        $this->dado['uf'] = $this->request->estado;
        $this->dado['usuario'] = $this->idUsuario;
        $this->dado['empresa'] = $this->idEmpresa;
        $this->dado['subempresa'] = $this->idSubempresa;

        if (!empty($this->request->vinculo)) {
            $this->pegarVinculo();
        }
    }

    private function pegarVinculo(): void
    {
        $loja = (new OrmHelper(TABELA_PARCEIRO_LOJA))->pegarUltimoRegistro(
            where: ['uuid', $this->request->vinculo],
            campo: ['id', 'titulo']
        );
        if (empty($loja)) {
            return;
        }
        $this->dado['vinculo'] = $loja['id'];
        $this->dado['vinculo_nome'] = $loja['titulo'];
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function salvar(): void
    {
        $this->dado($this->dado)->insert();
    }
}
