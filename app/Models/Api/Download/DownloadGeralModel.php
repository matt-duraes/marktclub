<?php

namespace App\Models\Api\Download;

use App\Models\Api\DownloadPrivado\ArquivoEntity;
use App\Models\Api\Painel\LogDownloadEntity;
use Http\Request;
use ORM\ORM;

abstract class DownloadGeralModel extends ORM
{
    public string $id = '';
    protected array $campoAceito = [];
    protected array $campo = [];
    protected string $usuario = '';
    protected array $busca = [];

    public function __construct(
        protected Request $request,
        string $tabela,
        private string $app
    ) {
        $this->ormTabela = $tabela;
        parent::__construct();
        $this->set(lista: $request->dado());
        $this->validarCampoAceito();
    }

    /**
     * Valida se os campos foram passados e são validos
     *
     * @param array $campo  Campos que o usuário enviou
     * @param array $aceito Campos aceitos
     *
     * @return
     */
    private function validarCampoAceito(): void
    {
        if (!$this->campo) {
            mensagemErro('Erro!', 'Você deve enviar pelo menos um campo.');
        }

        foreach ($this->campo as $item) {
            if (!in_array($item, $this->campoAceito)) {
                mensagemErro(
                    'Erro!',
                    'Um ou mais campos não tem permissão para serem buscados.',
                    status: 403,
                    localhost: 'O campo ' . $item . ' não está na lista de campos permitidos'
                );
            }
        }
    }

    abstract protected function buscarRegistro(): void;

    abstract protected function montarRetornoDownload(): void;

    /**
     * Salva o log do download
     *
     * @param array  $dado Dados que foram buscados
     * @param string $app  Nome do APP
     */
    protected function salvarLogDownload(): void
    {
        $Log = new LogDownloadEntity(
            app: $this->app,
            request: $this->request->dado(),
            quantidade: count($this->busca),
            usuario: $this->usuario
        );
        $Log->salvar();
    }

    /**
     * Valida se encontrou algum resultado
     *
     * @return
     */
    protected function validarBusca(): void
    {
        if (existeErro($this->busca, '0')) {
            return;
        }
        $this->erroDownloadPadrao();
    }

    /**
     * Mensagem de erro padrão
     *
     * @return
     */
    protected function erroDownloadPadrao(): void
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
    }

    /**
     * Salva o arquivo
     *
     */
    protected function salvarArquivo(): void
    {
        $Download = new ArquivoEntity($this->busca, $this->request->usuario);
        $Download->salvar();
        $this->id = $Download->id;
    }
}
