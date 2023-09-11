<?php

namespace App\Models\Api\UsuarioCliente\Senha;

use ORM\ORM;
use stdClass;
use Modules\Inteiro;
use App\Classes\UsuarioCliente\Hash;

final class ValidarCodigoModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private string $erroPadrao = 'Ocorreu um erro ao validar seu código, por favor, tente novamente.';
    private stdClass $usuario;
    public string $hash;

    public function __construct(
        private string $id,
        private Inteiro $codigo,
    ) {
        parent::__construct();
        $this->validarDado();
        $this->buscarUsuario();
        $this->validarCodigo();
        $this->criarHash();
    }

    private function validarDado()
    {
        if (empty($this->id)) {
            mensagemErro('Erro', $this->erroPadrao);
        } elseif ($this->codigo->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo código é obrigatório.');
        } elseif (!$this->codigo->valido()) {
            mensagemErro('Campo inválido!', 'O campo código não é válido.');
        }
    }

    private function buscarUsuario()
    {
        $usuario = $this
            ->campo(['id', 'codigo_valor', 'codigo_data'])
            ->where(['uuid', $this->id])
            ->primeiro();
        if (!$usuario) {
            mensagemErro('Erro!', 'Não foi possível encontrar seus dados, por favor, tente novamente.');
        }
        $this->usuario = $usuario;
    }

    private function validarCodigo()
    {
        if ((int)$this->usuario->codigo_valor != $this->codigo->numero()) {
            mensagemErro(
                'Campo inválido!',
                'O código enviado é inválido, caso tenha enviado mais de uma solicitação, o código anterior é invalidado.'
            );
        } elseif ($this->usuario->codigo_data < dataRemover(agora(), 10, 'minutos', 'Y-m-d H:i:s')) {
            mensagemErro(
                'Código vencido!',
                'O código enviado venceu, envie um novo código para recuperar sua senha.'
            );
        }
    }

    private function criarHash()
    {
        $hash = uuid();
        $dado = $this
            ->dado([
                'hash'      => $hash,
                'hash_data' => agora(),
                'hash_tipo' => Hash::RECUPERAR_SENHA
            ])
            ->where(['id', $this->usuario->id])
            ->update();
        if (!$dado) {
            mensagemErro('Erro!', $this->erroPadrao);
        }
        $this->hash = $hash;
    }
}
