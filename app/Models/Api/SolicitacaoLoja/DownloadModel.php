<?php

namespace App\Models\Api\SolicitacaoLoja;

use App\Models\Api\ParceiroLoja\Trait\MontarRetornoTrait;
use ORM\ORM;
use Http\Request;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Models\Api\ParceiroLoja\Trait\WhereTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class DownloadModel extends ORM
{
    use ValidarEmpresaTrait;
    use MontarRetornoTrait;
    use WhereTrait;

    public array $campo;
    public string $usuario;
    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        $this->validarCamposAceito();
    }

    public function download()
    {
        $campo = $this->campo;
        $dado = $this->buscarLojas($campo);

        if (!array_key_exists('0', $dado)) {
            $this->erroDownloadPadrao();
        }

        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado, $campo);
    }

    private function buscarLojas(array $campo)
    {
        $novoCampo = $this->converterCampoParaDownload($campo);
        $query = $this
            ->campo($novoCampo)
            ->where($this->pegarWhere(), false);

        $campoUsuario = [];
        if (in_array('usuario_nome', $campo)) {
            $campoUsuario[] = 'nome';
        }
        if (in_array('usuario_cpf', $campo)) {
            $campoUsuario[] = 'cpf';
        }
        if ($campoUsuario) {
            $query
                ->tabela(TABELA_USUARIO_CLIENTE)
                ->campo($campoUsuario, 'usuario')
                ->leftJoin('id', 'id_usuario_cliente');
        }

        $campoEmpresa = [];
        if (in_array('empresa_titulo', $campo)) {
            $campoEmpresa[] = 'titulo';
        }
        if ($campoEmpresa) {
            $query
                ->tabela(TABELA_COMERCIAL_EMPRESA)
                ->campo($campoEmpresa, 'empresa')
                ->leftJoin('id', 'id_admin_empresa');
        }

        $dado = $query->read();
        return $dado;
    }

    private function salvarLogDownload(array $dado)
    {
        $Log = new LogDownloadEntity(
            app: 'parceiro_loja',
            request: $this->request->dado(),
            quantidade: count($dado),
            usuario: $this->usuario
        );
        try {
            $Log->salvar();
        } catch (\Throwable) {
            $this->erroDownloadPadrao();
        }
    }

    private function erroDownloadPadrao()
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
    }

    private function montarRetornoDownload(array $dado, array $campo): array
    {
        $i = 0;
        $retorno = [];
        foreach ($dado as $linha) {
            foreach ($linha as $ind => $val) {
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        return $retorno;
    }

    private function validarCamposAceito(): void
    {
        $camposAceito = [
            'usuario_nome', 'usuario_cpf', 'empresa_titulo', 'nome',
            'telefone', 'email', 'mensagem', 'data_criacao',
            'data_atualizacao', 'status'
        ];

        $listaCampos = jsonDecode($this->request->campo, true, true);
        if (!$listaCampos) {
            mensagemErro('Erro!', 'Você deve enviar pelo menos um campo.');
        }

        foreach ($listaCampos as $campo) {
            if (!in_array($campo, $camposAceito)) {
                mensagemErro(
                    'Erro!',
                    'Um ou mais campos não tem permissão para serem buscados.',
                    status: 403,
                    localhost: 'O campo ' . $campo . ' não está na lista de campos permitidos'
                );
            }
        }
        return;
    }

    private function converterCampoParaDownload(array $campo)
    {
        $campo = array_flip($campo);
        if (array_key_exists('usuario_nome', $campo)) {
            unset($campo['usuario_nome']);
        }
        if (array_key_exists('usuario_cpf', $campo)) {
            unset($campo['usuario_cpf']);
        }
        if (array_key_exists('empresa_titulo', $campo)) {
            unset($campo['empresa_titulo']);
        }
        return array_keys($campo);
    }
}
