<?php

namespace ApiModel\Upload;

use ORM\Entity;
use Modules\Botao;
use App\Models\Api\UsuarioEquipe\PerfilModel;

final class GrupoEntity extends Entity
{

    protected string $_tabela = TABELA_UPLOAD_GRUPO;
    protected array $_buscar = ['id_upload_grupo', 'id_usuario_equipe', 'nome', 'extensao', 'diretorio', 'privado'];
    protected array $_insert = ['id_upload_grupo', 'id_usuario_equipe', 'extensao', 'diretorio', 'local', 'privado'];
    protected array $_salvar = ['nome'];

    public array $equipe = [];
    private array $raiz = [];
    private array $pai = [];
    private array $filho = [];
    private array $arquivo = [];
    public string $diretorio;
    public int $id_upload_grupo;
    public int $id_usuario_equipe;
    public array $extensao;
    public string $privado;

    /**
     * Busca ao setar o diretório e subdiretorio
     *
     * @param null|string $grupo    Grupo pai
     * @param null|string $nome     Nome do grupo
     */
    public function __construct(
        protected ?string $grupo = null,
        public ?string $nome = null,
    ) {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA PARA SALVAR
    |--------------------------------------------------------------------------
    */
    protected function regraSalvar()
    {
        $this->verificarSeNomeJaExiste();
    }

    protected function regraInsert()
    {
        $this->pegarPai();
        $this->id_upload_grupo = $this->pai['id'];
        $this->id_usuario_equipe = TOKEN['usuario']->get('id');
    }

    protected function regraUpdate()
    {
        if ($this->nome == $this->prop('nome')) {
            mensagemErro('Erro!', 'O novo nome para o diretório não pode ser igual o atual.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA PARA BUSCAR
    |--------------------------------------------------------------------------
    */
    protected function regraPosBuscar()
    {
        $this->pegarRaiz();
        $this->setarValorDaRaiz();
        $this->equipe = (new PerfilModel)->pegarDado($this->id_usuario_equipe);
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA PARA DESTRUIR
    |--------------------------------------------------------------------------
    */
    protected function regraDestruir()
    {
        $this->pegarFilhos();
        $this->pegarListaDeArquivoDoGrupo($this->filho);
        $this->pegarListaDeArquivoDoGrupo($this->prop('id'));
    }
    protected function regraPosDestruir()
    {
        foreach ($this->arquivo as $arquivo) {
            $path = DIRETORIO_PRIVADO . '/' . $this->diretorio . '/' . $arquivo;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */
    protected function getId()
    {
        return $this->prop('id');
    }
    protected function getExtensao()
    {
        return empty($this->extensao) ?
            [
                'jpeg', 'jpg', 'gif', 'png', 'svg', 'pdf', 'doc', 'docx',
                'ppt', 'pptx', 'csv', 'xls', 'xlsx', 'pdf'
            ] :
            $this->extensao;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function pegarListaDeArquivoDoGrupo(string|array $id): void
    {
        if (empty($id)) {
            return;
        }
        $id = is_array($id) ? $id : [$id];

        $Arquivo = new ArquivoModel();
        $lista = $Arquivo->pegarArquivosDoGrupo($id);
        foreach ($lista as $r) {
            $this->arquivo[] = $r->arquivo;
        }
        return;
    }

    private function pegarPai(): void
    {
        if (!empty($this->grupo)) {
            $where = ['uuid', $this->grupo];
        } else if (!empty($this->id_upload_grupo)) {
            $where = ['id', $this->id_upload_grupo];
        } else {
            mensagemErro('Erro!', '100001 - Ocorreu um erro, por favor, tente novamente.', localhost: 'Não foi possível pegar o diretório pai.');
        }
        $this->pai = $this->campo(['id'])->where($where)->primeiro(retorno: 'array');
    }

    private function pegarRaiz(): void
    {
        if (empty($this->id_upload_grupo)) {
            return;
        }

        $id = $this->id_upload_grupo;
        for ($i = 0; $i < 100; ++$i) {
            $dado = $this->campo(['id_upload_grupo', 'extensao', 'diretorio', 'privado'])->where(['id', $id])->primeiro(retorno: 'array');
            if (!array_key_exists('id_upload_grupo', $dado)) {
                return;
            } else if (empty($dado['id_upload_grupo'])) {
                $this->raiz = $dado;
                return;
            }
            $id = $dado['id_upload_grupo'];
        }
        return;
    }
    private function setarValorDaRaiz()
    {
        $raiz = $this->raiz;
        if (!$raiz) {
            return;
        }

        $this->extensao = jsonDecode($raiz['extensao'], true, true);
        $this->diretorio = $raiz['diretorio'];
        $this->privado = new Botao($raiz['privado']);
    }

    private function pegarFilhos(): void
    {
        $id = $this->prop('id');
        $lista = $this->campo(['id'])->where(['id_upload_grupo', $id])->read();
        foreach ($lista as $r) {
            $this->filho[] = $r->id;
        }
        return;
    }

    private function verificarSeNomeJaExiste(): void
    {
        $idPai = $this->id_upload_grupo;
        if (empty($idPai)) {
            return;
        }
        $where = [
            ['id_upload_grupo', $idPai],
            ['nome', $this->nome]
        ];
        if (!empty($this->id)) {
            $where[] = ['id', '!=', $this->prop('id')];
        }

        $lista = $this->campo(['nome'])->where($where)->read();

        if ($lista) {
            mensagemErro('Nome já existe!', 'Já existe um diretório com esse nome.');
        }
        return;
    }
}
