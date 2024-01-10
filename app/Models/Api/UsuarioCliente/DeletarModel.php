<?php

namespace App\Models\Api\UsuarioCliente;

use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use ORM\ORM;
use stdClass;

final class DeletarModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private int $idEmpresa;
    private array $usuario = [];

    public function __construct()
    {
        //$this->validarEmpresa('empresa');
        parent::__construct();
    }

    /**
     * @param string $id
     *
     * @throws Excecao
     */
    public function uuid(string $id): void
    {
        $usuario = $this
            ->where(
                $this->pegarWhereParaDeletar([
                    ['cod', $id],
                    ['status', 'in', Helper::STATUS_LIBERADO]
                ])
            )
            ->primeiro();
        $this->validarUsuarioPegarDependente($usuario);
    }

    /**
     * @param array $where
     *
     * @return array
     */
    private function pegarWhereParaDeletar(array $where): array
    {
        if (!empty($this->ormWherePadrao)) {
            $where[] = $this->ormWherePadrao;
        }
        return $where;
    }

    /**
     * @throws Excecao
     */
    private function validarUsuarioPegarDependente(stdClass|array $usuario): void
    {
        if (existeErro($usuario, 'id')) {
            mensagemStatus(404);
        }

        if ((new TipoUsuario($usuario->tipo))->indice() === TipoUsuario::TITULAR) {
            $this->pegarDependentes($usuario->id);
        }

        $this->setarUsuario($usuario);
    }

    /**
     * @param string $titular
     *
     * @throws Excecao
     */
    private function pegarDependentes(string $titular): void
    {
        $dependente = $this
            ->where([
                ['status', 'in', Helper::STATUS_LIBERADO],
                ['tipo', (new TipoUsuario(TipoUsuario::DEPENDENTE))->numero()],
                ['titular', $titular]
            ])
            ->read();

        if (!$dependente) {
            return;
        }

        if (!array_key_exists(0, $dependente)) {
            mensagemErro(
                'Erro!',
                'Ocorreu um erro ao deletar dependentes, por favor, tente novamente.'
            );
        }

        foreach ($dependente as $r) {
            $this->setarUsuario($r);
        }
    }

    /**
     * @param $usuario
     *
     * @throws Excecao
     */
    private function setarUsuario($usuario): void
    {
        if (empty($usuario->id)) {
            $this->erroPadrao();
        }

        $id = $usuario->id;

        unset($usuario->id, $usuario->cod, $usuario->empresa, $usuario->id_admin_subempresa, $usuario->titular, $usuario->tipo, $usuario->federacao, $usuario->uf, $usuario->cidade, $usuario->data_criacao, $usuario->data_atualizacao, $usuario->data_acesso, $usuario->data_password, $usuario->data_online, $usuario->data_upload_tabela, $usuario->status);

        $dado = [
            'status' => 4
        ];

        foreach (array_keys((array)$usuario) as $val) {
            $dado[$val] = '';
        }

        $this->usuario[] = ['id' => $id, 'dado' => $dado];
    }

    /**
     * @throws Excecao
     */
    private function erroPadrao(): void
    {
        mensagemErro(
            'Erro ao deletar!',
            'Ocorreu um erro ao deletar usuário, por favor, tente novamente.'
        );
    }

    /**
     * @param string $cpf
     *
     * @throws Excecao
     */
    public function cpf(string $cpf): void
    {
        $usuario = $this
            ->where(
                $this->pegarWhereParaDeletar([
                    ['documento', soNumero($cpf)],
                    ['status', 'in', Helper::STATUS_LIBERADO]
                ])
            )
            ->primeiro();
        $this->validarUsuarioPegarDependente($usuario);
    }

    /**
     * @return bool
     * @throws Excecao
     */
    public function deletar(): bool
    {
        foreach ($this->usuario as $r) {
            $id = $r['id'];
            $dado = $r['dado'];
            $deletar = $this
                ->dado($dado)
                ->where(
                    $this->pegarWhereParaDeletar([
                        ['id', $id]
                    ])
                )
                ->update();

            if (existeErro($deletar, 'id')) {
                $this->erroPadrao();
            }
        }
        return true;
    }
}
