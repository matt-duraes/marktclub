<?php

namespace Painel\Demanda\Models;

use ORM\Entity;
use Erro\Excecao;
use Helpers\TextoHelper;
use Helpers\UploadHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ArquivoEntity extends Entity
{
    protected string $_tabela = TABELA_DEMANDA_ARQUIVO;

    protected array $_insert = [
        'id_demanda_tarefa',
        'id_usuario_equipe',
        'arquivo' => '->arquivoSalvar',
        'extensao',
        'tipo',
        'nome'
    ];
    protected array $_update = ['nome'];
    protected array $_buscar = ['id_demanda_tarefa', 'nome', 'arquivo', 'extensao', 'tipo', 'nome'];

    private ?MensagemEntity $Mensagem;
    private string $nomeAntigo;

    protected string $arquivo;
    protected UploadHelper $arquivoSalvar;
    public string $extensao;
    public string $nome;
    public string $tipo;
    public string $link;

    /**
     * Cria um novo arquivo
     *
     * @param null|string           $demanda    ID da tarefa que será vinculado o arquivo
     * @param null|UploadedFile     $upload     Arquivo enviado pelo Request
     */
    public function __construct(
        private ?string $tarefa = null,
        private ?UploadedFile $upload = null
    ) {
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        $this->link = LINK_PRIVADO . '/demanda/' . $this->arquivo;
    }

    protected function regraInsert()
    {
        $this->pegarTarefa();
        $this->criarArquivo();

        $Texto = new TextoHelper();
        $this->nome = $Texto->valor(
            preg_replace("/\.[a-zA-Z]{3,4}$/", '', $this->upload->getClientOriginalName())
        )->cortar(150, '', true)->r();
        $this->extensao = $this->arquivoSalvar->extensao();
        $this->tipo = in_array($this->extensao, ['png', 'jpg', 'gif', 'svg', 'jpeg']) ? 'imagem' : 'arquivo';
        $this->id_usuario_equipe = sessao('USUARIO.id');
    }

    protected function regraPosInsert()
    {
        $this->criarNovaMensagem(
            'O usuário <strong>' . sessao('USUARIO.nome') .
                '</strong> adicionou o arquivo <strong>' . $this->nome . '</strong> nessa tarefa.'
        );
    }

    protected function regraUpdate()
    {
        $this->nomeAntigo = $this->prop('nome');
        if (empty($this->nome)) {
            mensagemErro('Campo obrigatório!', 'Você precisa enviar um nome para o arquivo.');
        } elseif (strlen($this->nome) > 150) {
            mensagemErro('Campo inválido!', 'Você precisa enviar um nome com no máximo 150 caracteres.');
        } elseif ($this->nomeAntigo == $this->nome) {
            mensagemErro('Campo inválido!', 'Você deve mudar o nome do arquivo para continuar.');
        }
    }

    protected function regraPosUpdate()
    {
        $this->criarNovaMensagem(
            'O usuário <strong>' . sessao('USUARIO.nome') . '</strong> renomeou o ' .
                'arquivo <strong>' . $this->nomeAntigo . '</strong> para <strong>' . $this->nome . '</strong>.'
        );
    }

    protected function regraPosDestruir()
    {
        $path = DIRETORIO_PRIVADO . '/demanda/' . $this->arquivo;
        if (file_exists($path)) {
            unlink($path);
        }

        $this->criarNovaMensagem(
            'O usuário <strong>' . sessao('USUARIO.nome') . '</strong> deletou o ' .
                'arquivo <strong>' . $this->nome . '</strong>.'
        );
    }

    private function criarNovaMensagem($mensagem)
    {
        try {
            $this->Mensagem = new MensagemEntity(
                tarefa: $this->id_demanda_tarefa,
                texto: $mensagem
            );
            $this->Mensagem->salvar();
        } catch (\Throwable) {
            $this->Mensagem = null;
        }
    }

    /**
     * Pega a mensagem ao inserir um novo arquivo
     */
    public function pegarMensagem(): string
    {
        if ($this->Mensagem instanceof MensagemEntity) {
            return $this->Mensagem->texto;
        }
        return '';
    }

    private function pegarTarefa()
    {
        $Tarefa = new TarefaEntity();
        try {
            $Tarefa->id($this->tarefa);
            $this->id_demanda_tarefa = $Tarefa->get('id');
        } catch (\Throwable) {
            throw new Excecao('Erro!', 'Não foi possível encontrar a tarefa.');
        }
    }

    private function criarArquivo()
    {
        $this->arquivoSalvar = new UploadHelper(
            arquivo: $this->upload,
            diretorio: 'demanda',
            ext: [
                'doc', 'docx', 'csv', 'xls', 'xlsx', 'pdf', 'png', 'jpg', 'jpeg',
                'svg', 'gif', 'ppt', 'pptx', 'psd', 'ai'
            ],
            nome: md5(uniqid(time()))
        );
    }
}
