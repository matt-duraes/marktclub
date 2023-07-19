<?php

namespace Helpers;

use stdClass;

trait HelperTrait
{
    /**
     * Pegar o ID do registro pelo UUID
     *
     * @param  string      $uuid         O UUID do registro que deseja buscar
     * @param  string|null $erroMensagem Mensagem de erro caso queira dar erro se não existir o registro
     * @param  string|null $erroTitulo   Título para a mensagem de erro (opcional)
     * @return int
     */
    public function pegarIdPeloUuid(string $uuid, string $erroMensagem = null, string $erroTitulo = null): int
    {
        return $this->pegarCampoPor('id', ['uuid', $uuid], 0, 'DESC', $erroMensagem, $erroTitulo);
    }

    /**
     * Pegar o ID do registro pelo UUID
     *
     * @param  string      $id           O ID do registro que deseja buscar
     * @param  string|null $erroMensagem Mensagem de erro caso queira dar erro se não existir o registro
     * @param  string|null $erroTitulo   Título para a mensagem de erro (opcional)
     * @return string
     */
    public function pegarUuidPeloId(int $id, string $erroMensagem = null, string $erroTitulo = null): string
    {
        return $this->pegarCampoPor('uuid', ['id', $id], '', 'DESC', $erroMensagem, $erroTitulo);
    }

    /**
     * Pegar um campo especifico
     *
     * @param string      $campo        Campo que deseja pegar
     * @param string      $where        Where para fazer a busca
     * @param mixed       $padrao       Retorno padrão caso não ache e não queira dar erro
     * @param string      $ordem        Ordem da busca podendo ser DESC ou ASC
     * @param string|null $erroMensagem Mensagem de erro caso queira dar erro se não existir o registro
     * @param string|null $erroTitulo   Título para a mensagem de erro (opcional)
     */
    public function pegarCampoPor(
        string $campo,
        array $where,
        mixed $padrao = null,
        string $ordem = 'DESC',
        string $erroMensagem = null,
        string $erroTitulo = null
    ) {
        $valor = $this->campo([$campo])->where($where)->order('id', $ordem)->primeiro($campo, $padrao);
        return $this->pegarRetorno($valor, $erroTitulo, $erroMensagem);
    }

    /**
     * Pega uma lista de UUID e retorna uma lista de ID
     *
     * @param  array $uuid Lista de UUID que desaja converter
     * @return array
     */
    public function mudarListaUuidParaId(array $uuid): array
    {
        $lista = $this->campo(['id'])->where(['uuid', 'in', $uuid])->read();
        $id = [];
        foreach ($lista as $r) {
            $id[] = $r->id;
        }
        return $id;
    }

    /**
     * Pega uma lista de ID e retorna uma lista de UUID
     *
     * @param  array $id Lista de ID que desaja converter
     * @return array
     */
    public function mudarListaIdParaUuid(array $id): array
    {
        $lista = $this->campo(['uuid'])->where(['id', 'in', $id])->read();
        $uuid = [];
        foreach ($lista as $r) {
            $uuid[] = $r->uuid;
        }
        return $uuid;
    }

    /**
     * Pegar o último registro
     *
     * @param  array          $where        Where para a busca
     * @param  array|null     $campo        Lista de campos que deseja buscar
     * @param  string         $retorno      Tipo de retorno podendo ser um array ou object
     * @param  string|null    $erroMensagem Mensagem de erro caso queira dar erro se não existir o registro
     * @param  string|null    $erroTitulo   Título para a mensagem de erro (opcional)
     * @return array|stdClass
     */
    public function pegarUltimoRegistro(
        array $where,
        array $campo = null,
        $retorno = 'array',
        string $erroMensagem = null,
        string $erroTitulo = null,
    ): array|stdClass {
        return $this->pegarUmRegistro($where, $campo, 'DESC', $retorno, $erroMensagem, $erroTitulo);
    }

    /**
     * Pegar o primeiro registro
     *
     * @param  array          $where        Where para a busca
     * @param  array|null     $campo        Lista de campos que deseja buscar
     * @param  string         $retorno      Tipo de retorno podendo ser um array ou object
     * @param  string|null    $erroMensagem Mensagem de erro caso queira dar erro se não existir o registro
     * @param  string|null    $erroTitulo   Título para a mensagem de erro (opcional)
     * @return array|stdClass
     */
    public function pegarPrimeiroRegistro(
        array $where,
        array $campo = null,
        $retorno = 'array',
        string $erroMensagem = null,
        string $erroTitulo = null,
    ): array|stdClass {
        return $this->pegarUmRegistro($where, $campo, 'ASC', $retorno, $erroMensagem, $erroTitulo);
    }

    private function pegarUmRegistro(
        array $where,
        array $campo = null,
        string $ordem = 'DESC',
        string $retorno = 'array',
        string $erroMensagem = null,
        string $erroTitulo = null
    ): array|stdClass {
        $campo = empty($campo) ? ['*'] : $campo;
        $valor = $this
            ->campo($campo)
            ->where($where)
            ->order('id', $ordem)
            ->primeiro(retorno: in_array($retorno, ['object', 'array']) ? $retorno : 'array');
        return $this->pegarRetorno($valor, $erroTitulo, $erroMensagem);
    }

    private function pegarRetorno($valor, string $titulo = null, string $mensagem = null)
    {
        if (empty($valor) && !empty($mensagem)) {
            mensagemErro(titulo: !empty($titulo) ? $titulo : 'Erro!', mensagem: $mensagem);
        }
        return $valor;
    }
}
