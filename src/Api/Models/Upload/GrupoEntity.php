<?php

namespace ApiModel\Upload;

use Erro\Erro;
use ORM\Entity;
use Erro\Excecao;
use Modules\Botao;
use App\Models\Api\UsuarioEquipe\PerfilModel;

final class GrupoEntity extends Entity
{
    /**
     * @var array
     */
    public array $equipe = [];

    /**
     * @var string
     */
    public string $diretorio;

    /**
     * @var int
     */
    public int $id_upload_grupo;

    /**
     * @var int
     */
    public int $id_usuario_equipe;

    /**
     * @var array
     */
    public array $extensao;

    /**
     * @var string|null
     */
    public Botao $privado;

    /**
     * @var string
     */
    protected string $ormTabela = TABELA_UPLOAD_GRUPO;

    /**
     * @var array|string[]
     */
    protected array $ormBuscar = ['id_upload_grupo', 'id_usuario_equipe', 'nome', 'extensao', 'diretorio', 'privado'];

    /**
     * @var array|string[]
     */
    protected array $ormInsert = ['id_upload_grupo', 'id_usuario_equipe', 'extensao', 'diretorio', 'local', 'privado'];

    /**
     * @var array|string[]
     */
    protected array $ormSalvar = ['nome'];

    /**
     * @var array
     */
    private array $pai = [];

    /**
     * @var array
     */
    private array $filho = [];

    /**
     * @var array
     */
    private array $arquivo = [];

    /**
     * Busca ao setar o diretório e subdiretorio
     *
     * @param null|string $grupo Grupo pai
     * @param null|string $nome  Nome do grupo
     */
    public function __construct(
        protected ?string $grupo = null,
        public ?string $nome = null,
    ) {
        parent::__construct();
    }

    /**
     */
    protected function regraSalvar(): void
    {
        try {
            $this->verificarSeNomeJaExiste();
        } catch (Erro | Excecao) {
        }
    }

    /**
     * @throws Erro
     * @throws Excecao
     */
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

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->pegarPai();
        $this->id_upload_grupo = $this->pai['id'];
        $this->extensao = jsonDecode($this->pai['extensao'], true, true);
        $this->diretorio = $this->pai['diretorio'];
        $this->privado = new Botao($this->pai['privado']);
        $this->id_usuario_equipe = TOKEN['usuario']->id;
    }

    /**
     * @throws Excecao
     */
    private function pegarPai(): void
    {
        if (!empty($this->grupo)) {
            $where = ['uuid', $this->grupo];
        } elseif (!empty($this->id_upload_grupo)) {
            $where = ['id', $this->id_upload_grupo];
        } else {
            mensagemErro(
                'Erro!',
                '100001 - Ocorreu um erro, por favor, tente novamente.',
                localhost: 'Não foi possível pegar o diretório pai.'
            );
        }
        $this->pai = $this->campo(['id', 'diretorio', 'extensao', 'privado'])->where($where)->primeiro(retorno: 'array');
    }

    /**
     * @throws Erro
     * @throws Excecao
     */
    protected function regraUpdate(): void
    {
        if ($this->nome == $this->prop('nome')) {
            mensagemErro('Erro!', 'O novo nome para o diretório não pode ser igual o atual.');
        }
    }

    /**
     * @throws Excecao
     */
    protected function regraPosBuscar(): void
    {
        $this->equipe = (new PerfilModel())->pegarDado($this->id_usuario_equipe);
    }

    /**
     * @throws Erro
     * @throws Excecao
     */
    protected function regraDestruir()
    {
        $this->pegarFilhos();
        $this->pegarListaDeArquivoDoGrupo($this->filho);
        $this->pegarListaDeArquivoDoGrupo($this->prop('id'));
    }

    /**
     * @throws Erro
     * @throws Excecao
     */
    private function pegarFilhos(): void
    {
        $id = $this->prop('id');
        $lista = $this->campo(['id'])->where(['id_upload_grupo', $id])->read();
        foreach ($lista as $r) {
            $this->filho[] = $r->id;
        }
        return;
    }

    /**
     * @param  string|array $id
     * @throws Excecao
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

    /**
     */
    protected function regraPosDestruir(): void
    {
        foreach ($this->arquivo as $arquivo) {
            $path = DIRETORIO_PRIVADO . '/' . $this->diretorio . '/' . $arquivo;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * @return mixed
     * @throws Erro
     * @throws Excecao
     */
    protected function getId(): mixed
    {
        return $this->prop('id');
    }

    /**
     * @return array|string[]
     */
    protected function getExtensao(): array
    {
        return empty($this->extensao) ?
            [
                'jpeg',
                'jpg',
                'gif',
                'png',
                'svg',
                'pdf',
                'doc',
                'docx',
                'ppt',
                'pptx',
                'csv',
                'xls',
                'xlsx',
                'pdf'
            ] :
            $this->extensao;
    }
}
