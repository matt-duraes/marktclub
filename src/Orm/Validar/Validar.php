<?php

namespace ORM\Validar;

use PDO;
use Status\StatusInterface;
use Modules\ModuleInterface;

trait Validar
{
    /**
     * Valida se o valor já existe no banco de dados
     *
     * @param  string      $campo
     * @param  string      $mensagem
     * @param  string|null $titulo
     * @return bool
     */
    public function validarCampoDuplicado(string $campo, string $mensagem, ?string $titulo = null): void
    {
        if (!$this->propriedadeExiste($campo)) {
            return;
        }

        $valor = $this->$campo;
        $valor = $valor instanceof ModuleInterface || $valor instanceof StatusInterface ?
            $valor->banco() : $valor;

        $where = "`{$campo}` = :{$campo}";
        $campoEmpresa = 'id_admin_empresa';
        if ($this->ormEntityExiste) {
            $where .= " AND `{$campoEmpresa}` = :{$campoEmpresa}";
        }

        $DB = $this->ormLeitura ? $this->ormDBLeitura : $this->ormDBEscrita;
        $sql = $DB->prepare("SELECT `id` FROM `{$this->ormTabela}` WHERE {$where}");
        ppe($sql);
        $sql->bindValue(":{$campo}", $valor);
        if ($this->ormEntityExiste) {
            $sql->bindValue(":{$campoEmpresa}", $this->idEmpresa, PDO::PARAM_STR);
        }

        try {
            $run = $sql->execute();
        } catch (\Throwable $e) {
            $run = false;
        }
        ppe($run);

        // $this->ormLeitura
        // if ($this->existe($where)) {
        //     mensagemErro('CPF já existe!', 'O CPF informado já está em uso por outro usuário.');
        // }
    }
}
