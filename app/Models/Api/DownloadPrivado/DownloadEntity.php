<?php

namespace App\Models\Api\DownloadPrivado;

use ORM\Entity;
use Modules\Nome;
use Modules\Email;
use Modules\DataHora;
use Helpers\EmailHelper;

final class DownloadEntity extends Entity
{
    protected string $_tabela = TABELA_DOWNLOAD_PRIVADO;
    protected array $_buscar = ['nome', 'email', 'arquivo', 'codigo_email', 'codigo_autorizacao', 'data_vencimento'];
    protected array $_update = ['codigo_email', 'codigo_autorizacao'];

    public Nome $nome;
    public DataHora $data_vencimento;
    public Email $email;

    protected function regraPosBuscar()
    {
        if ($this->data_vencimento->date() <= agora()) {
            mensagemStatus(404);
        }
    }

    /**
     * Gerar código
     */
    public function gerarCodigoEmail()
    {
        $codigo = strCodigo(8, false, false);
        $this->salvarCodigoEmail($codigo);
        $this->enviarCodigoPorEmail($codigo);
    }
    /**
     * Validar código enviado
     */
    public function validarCodigoEmail($codigo)
    {
        if ($codigo != $this->codigo_email) {
            mensagemErro('Código inválido!', 'Verifique o código informado e tente novamente.');
        }
        $this->salvarCodigoAutorizacao();
        return true;
    }

    public function validarCodigoAutorizacao($codigo)
    {
        if ($codigo != $this->codigo_autorizacao) {
            mensagemStatus(404);
        }
        $this->codigo_autorizacao = '';
        $this->salvar();
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODO PRIVADO
    |--------------------------------------------------------------------------
    */
    private function salvarCodigoEmail($codigo)
    {
        $this->codigo_email = $codigo;
        $this->salvar();
    }
    private function enviarCodigoPorEmail($codigo)
    {
        $titulo = 'Código de desbloqueio';
        $Email = new EmailHelper();
        $Email->mensagem(
            titulo: $titulo,
            mensagem: 'Olá <strong>' . $this->nome->primeiroNome() . '</strong>, para desbloquear seu download, use o codigo abaixo:',
            codigo: $codigo,
            observacao: 'Esse é um e-mail privado, caso não tenha solicitado, delete-o e entre em contato com o Markt Club.'
        );
        $Email->sendGrid($titulo, $this->nome->nome(), $this->email->email());
    }
    private function salvarCodigoAutorizacao()
    {
        $this->codigo_email = '';
        $this->codigo_autorizacao = uuid();
        $this->salvar();
    }
}
