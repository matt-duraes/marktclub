<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;
use stdClass;
use Http\Request;
use Modules\Data;
use Modules\Genero;
use Modules\DataHora;
use Modules\Telefone;
use Modules\EstadoCivil;
use App\Classes\UsuarioCliente\Ordem;
use App\Classes\UsuarioCliente\Status;
use App\Models\Api\Painel\LogDownloadEntity;

final class ClienteModel extends ORM
{
    protected string $_tabela = TABELA_USUARIO_NOVO;

    private int $idEmpresa;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no UsuarioCliente\ClienteModel');
        }
        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->validarCampoDoRequest();
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */
    public function download()
    {
        $this->validarCamposAceito();
        $campo = $this->converterCampoParaDownload();

        $dado = $this->buscarUsuario($campo, false);
        if (!array_key_exists('0', $dado)) {
            $this->erroDownloadPadrao();
        }
        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado);
    }
    private function salvarLogDownload(array $dado)
    {
        $Log = new LogDownloadEntity(
            app: 'usuario_cliente',
            request: $this->request->dado(),
            quantidade: count($dado)
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
    private function montarRetornoDownload(array $dado): array
    {
        $i = 0;
        $retorno = [];
        foreach ($dado as $linha) {
            foreach ($linha as $ind => $val) {
                if ($ind == 'documento') {
                    $ind = 'cpf';
                    $val = strCpf($val);
                } else if ($ind == 'documento_rg') {
                    $ind = 'rg';
                    $val = strNull($val);
                } else if ($ind == 'aniversario') {
                    $ind = 'data_nascimento';
                    $val = dataBr($val);
                } else if ($ind == 'data_upload_tabela') {
                    $ind = 'data_upload';
                    $val = dataBr($val);
                } else if ($ind == 'sexo') {
                    $ind = 'genero';
                    $val = (new Genero($val))->genero();
                } else if ($ind == 'estado_civil') {
                    $val = (new EstadoCivil($val))->estadoCivil();
                } else if ($ind == 'cidade') {
                    $ind = 'endereco_cidade';
                    $val = strNull($val);
                } else if ($ind == 'uf') {
                    $ind = 'endereco_estado';
                    $val = strNull($val);
                } else if ($ind == 'telefone_fixo') {
                    $ind = 'telefone_trabalho';
                    $val = (new Telefone($val))->telefone();
                } else if ($ind == 'telefone_celular') {
                    $ind = 'telefone_pessoal';
                    $val = (new Telefone($val))->telefone();
                } else if (in_array($ind, ['data_criacao', 'data_atualizacao', 'data_acesso'])) {
                    $val = (new DataHora($val))->data();
                } else if ($ind == 'tipo') {
                    $val = [1 => 'titular', 2 => 'dependente', 3 => 'admin'][$val] ?? '';
                } else if ($ind == 'status') {
                    $val = (new Status($val))->indice();
                } else {
                    $val = strNull($val);
                }
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        return $retorno;
    }
    private function validarCamposAceito(): void
    {
        $camposAceito = [
            'nome', 'cpf', 'rg', 'siape', 'matricula', 'data_nascimento', 'genero', 'estado_civil',
            'email_pessoal', 'email_trabalho', 'email_funcional', 'telefone_pessoal', 'telefone_trabalho', 'endereco_cep',
            'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'endereco_cidade',
            'endereco_estado', 'data_criacao', 'data_atualizacao', 'data_acesso', 'tipo', 'federacao', 'grupo',
            'status', 'data_upload'
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

    private function converterCampoParaDownload()
    {
        $campo = array_flip(jsonDecode($this->request->campo, true, true));
        if (array_key_exists('cpf', $campo)) {
            unset($campo['cpf']);
            $campo['documento'] = true;
        }
        if (array_key_exists('rg', $campo)) {
            unset($campo['rg']);
            $campo['documento_rg'] = true;
        }
        if (array_key_exists('telefone_pessoal', $campo)) {
            unset($campo['telefone_pessoal']);
            $campo['telefone_celular'] = true;
        }
        if (array_key_exists('telefone_trabalho', $campo)) {
            unset($campo['telefone_trabalho']);
            $campo['telefone_fixo'] = true;
        }
        if (array_key_exists('data_nascimento', $campo)) {
            unset($campo['data_nascimento']);
            $campo['aniversario'] = true;
        }
        if (array_key_exists('genero', $campo)) {
            unset($campo['genero']);
            $campo['sexo'] = true;
        }
        if (array_key_exists('endereco_cidade', $campo)) {
            unset($campo['endereco_cidade']);
            $campo['cidade'] = true;
        }
        if (array_key_exists('endereco_estado', $campo)) {
            unset($campo['endereco_estado']);
            $campo['uf'] = true;
        }
        if (array_key_exists('data_upload', $campo)) {
            unset($campo['data_upload']);
            $campo['data_upload_tabela'] = true;
        }
        return array_keys($campo);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA USUÁRIOS
    |--------------------------------------------------------------------------
    */
    public function listar()
    {
        $dado = $this->buscarUsuario([
            'cod', 'nome', 'documento', 'email_trabalho', 'email_pessoal', 'status', 'data_criacao'
        ], true);

        $dado->lista = $this->montarRetornoLista($dado->lista);
        return $dado;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE BUSCA
    |--------------------------------------------------------------------------
    */
    private function buscarUsuario(array $campo, bool $paginacao): stdClass|array
    {
        $request = $this->request;

        $ordem = new Ordem($request->chave('ordem', ''));
        $where = $this->pegarWhere();

        $query = $this->campo($campo)->where($where)->order($ordem);

        if ($paginacao) {
            $pagina = $this->pegarPagina();
            $query->pagina($pagina, 50);
        }

        $pagamento = $request->pagamento;
        if ($pagamento == 'sim') {
            $query
                ->tabela(TABELA_USUARIO_PAGAMENTO)->join('id_usuario_cliente', 'id')
                ->where(['status', 1])->group('id_usuario_cliente');
        }

        return $query->read();
    }

    private function montarRetornoLista($dado)
    {
        if (!$dado) {
            return [];
        }

        $lista = [];
        foreach ($dado as $r) {
            $email = null;
            if (!empty($r->email_pessoal)) {
                $email = $r->email_pessoal;
            } else if (!empty($r->email_trabalho)) {
                $email = $r->email_trabalho;
            }
            $lista[] = [
                'id' => $r->cod,
                'nome' => $r->nome,
                'cpf' => $r->documento,
                'email' => $email,
                'data_criacao' => $r->data_criacao,
                'status' => (new Status($r->status))->indice(),
            ];
        }
        return $lista;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS GERAIS
    |--------------------------------------------------------------------------
    */
    private function pegarPagina(): int
    {
        $pagina = $this->request->chave('pagina', 1);
        return preg_match('/[0-9]+/', $pagina) && $pagina > 0 ? $pagina : 1;
    }

    private function pegarWhere(): array
    {
        $request = $this->request;

        $where = [
            ['empresa', $this->idEmpresa],
            ['tipo', 'in', [1, 3]]
        ];

        // Colocando para aparecer só quem tem data de ativação na FENAE
        if ($this->idEmpresa == 153) {
            $where[] = ['data_ativacao', 'notnull'];
        }

        $pesquisa = $request->pesquisa;
        if (!empty($pesquisa)) {
            $wherePesquisa = [
                'OR',
                ['nome', 'like', '%' . $pesquisa . '%'],
                ['email_pessoal', 'like', $pesquisa . '%'],
                ['email_trabalho', 'like', $pesquisa . '%']
            ];
            $documento = soNumero($pesquisa);
            if (!empty($documento)) {
                $wherePesquisa[] = ['documento', 'like', $documento . '%'];
            }
            $where[] = $wherePesquisa;
        }

        // nome
        $nome = $request->nome;
        if (!empty($nome)) {
            $where[] = ['nome', 'like', '%' . $nome . '%'];
        }

        // email
        $email = $request->email;
        if (!empty($nome)) {
            $where[] = [
                'OR',
                ['email_pessoal', 'like', $email . '%'],
                ['email_trabalho', 'like', $email . '%'],
            ];
        }

        // cpf
        $cpf = soNumero($request->cpf);
        if (!empty($cpf)) {
            $where[] = ['documento', 'like', $cpf . '%'];
        }

        // matricula
        $matricula = soNumero($request->matricula);
        if (!empty($matricula)) {
            $where[] = ['matricula', 'like', $matricula . '%'];
        }

        // siape
        $siape = soNumero($request->siape);
        if (!empty($siape)) {
            $where[] = ['siape', 'like', $siape . '%'];
        }

        // data upload
        $dataUpload = $request->data_upload;
        if (validarDate($dataUpload)) {
            $where[] = ['data_upload_tabela', dataBanco($dataUpload)];
        }

        // data criacao de
        $dataCriacaoDe = $request->data_criacao_de;
        if (validarDate($dataCriacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataCriacaoDe)];
        }
        // data criacao ate
        $dataCriacaoAte = $request->data_criacao_ate;
        if (validarDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataCriacaoAte) . ' 23:59:59'];
        }

        // status
        $status = new Status($request->status);
        if ($status->valido() && $status->indice() != 'deletado') {
            $where[] = ['status', $status->numero()];
        } else {
            $where[] = ['status', 'in', [1, 2, 3, 5]];
        }

        return $where;
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR LEED
    |--------------------------------------------------------------------------
    */
    public function salvarLead(array $dado)
    {
        $cpf = $dado['documento'];
        if ($this->existe([
            ['documento', $cpf],
            ['empresa', $this->idEmpresa]
        ])) {
            return false;
        }

        if ($this->removerEmailJaExiste($dado['email_pessoal'])) {
            unset($dado['email_pessoal']);
        }
        if ($this->removerEmailJaExiste($dado['email_trabalho'])) {
            unset($dado['email_trabalho']);
        }
        if ($this->removerEmailJaExiste($dado['email_funcional'])) {
            unset($dado['email_funcional']);
        }

        $dado += [
            'cod' => uuid(),
            'empresa' => $this->idEmpresa,
            'titular' => null,
            'tipo' => 1,
            'status' => 2
        ];

        $salvar = $this->dado($dado)->insert();
        if (existeErro($salvar, 'id')) {
            mensagemErro('Erro!', 'O Status foi alterado mas ouve um erro ao salvar usuário.');
        }
    }
    private function removerEmailJaExiste($email)
    {
        return $this->existe([
            ['empresa', $this->idEmpresa],
            [
                'OR',
                ['email_pessoal', $email],
                ['email_trabalho', $email],
                ['email_funcional', $email],
            ]
        ]);
    }

    private function validarCampoDoRequest()
    {
        if (is_null($this->request)) {
            return;
        }

        $ordem = new Ordem($this->request->ordem);
        $status = new Status($this->request->status);
        $dataUpload = new Data($this->request->data_upload);
        $dataCriacaoDe = new Data($this->request->data_criacao_de);
        $dataCriacaoAte = new Data($this->request->data_criacao_ate);

        if (!empty($this->request->pagina) && !preg_match('/^[1-9]{1}[0-9]*$/', $this->request->pagina)) {
            mensagemErro('Campo inválido!', 'A página deve ser um número inteiro.');
        } else if (!$ordem->vazio() && !$ordem->valido()) {
            mensagemErro('Campo inválido!', 'A ordem informada não é um valor válido.');
        } else if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é um valor válido.');
        } else if (!$dataUpload->vazio() && !validarDate($dataUpload)) {
            mensagemErro('Campo inválido!', 'A data de upload informado não é um valor válido.');
        } else if (!$dataCriacaoDe->vazio() && !validarDate($dataCriacaoDe)) {
            mensagemErro('Campo inválido!', 'A data de criação do começo informado não é um valor válido.');
        } else if (!$dataCriacaoAte->vazio() && !validarDate($dataCriacaoAte)) {
            mensagemErro('Campo inválido!', 'A data de criação final informado não é um valor válido.');
        }
    }
}
