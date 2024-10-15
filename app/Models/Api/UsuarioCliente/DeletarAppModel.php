<?php

namespace App\Models\Api\UsuarioCliente;

use App\Classes\UsuarioCliente\Status;
use Erro\Excecao;
use Helpers\OrmHelper;
use ORM\ORM;

class DeletarAppModel extends ORM
{
    public array $success = [];
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    /**
     * @param string|int|null $empresa
     * @param string|int|null $subempresa
     * @param string|int|null $usuario
     *
     * @throws Excecao
     */
    public function __construct(
        private string|int|null $empresa = null,
        private string|int|null $subempresa = null,
        private string|int|null $usuario = null
    ) {
        $this->validarRequest();
        $this->converterUuidParaId();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (empty($this->empresa) || empty($this->usuario)) {
            mensagemErro(
                'Operação inválida!!',
                'Não conseguimos prosseguir com está operação. Verifique as informações!!'
            );
        }
        if (!validarUuid($this->empresa, false)) {
            mensagemErro(
                'Operação inválida!!',
                'Não conseguimos identificar a empresa. Verifique as informações!!'
            );
        }
        /*if (!validarUuid($this->subempresa, false)) {
            mensagemErro(
                'Operação inválida!!',
                'Não conseguimos identificar a subempresa. Verifique as informações!!'
            );
        }*/
        if (!validarUuid($this->usuario, false)) {
            mensagemErro(
                'Operação inválida!!',
                'Não conseguimos identificar a usuário. Verifique as informações!!'
            );
        }
    }

    /**
     * @return void
     */
    private function converterUuidParaId(): void
    {
        $this->empresa = $this->buscarId(TABELA_COMERCIAL_EMPRESA, $this->empresa);
        /*$this->subempresa = $this->buscarId(TABELA_COMERCIAL_EMPRESA, $this->subempresa);*/
        $this->usuario = $this->buscarId(TABELA_USUARIO_CLIENTE, $this->usuario);
    }

    /**
     * @param string $tabela
     * @param string $uuid
     *
     * @return int
     */
    private function buscarId(string $tabela, string $uuid): int
    {
        $OrmHelper = new ORMHelper($tabela);
        return $OrmHelper->pegarIdPeloUuid($uuid);
    }

    /**
     * @throws Excecao
     */
    public function bloquearUsuario(): void
    {
        $this
            ->where($this->pegarWhere())
            ->dado([
                'status' => (new Status(Status::BLOQUEADO))->numero()
            ])
            ->update();
        $this->success['id'] = uuid();
    }

    /**
     * @return array[]
     */
    private function pegarWhere(): array
    {
        $where = [
            ['id', $this->usuario],
            ['id_admin_empresa', $this->empresa]
        ];
        if (!empty($this->subempresa)) {
            $where[] = ['id_admin_subempresa', $this->subempresa];
        }
        return $where;
    }
}
