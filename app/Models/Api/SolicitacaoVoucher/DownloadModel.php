<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\ORM;
use Http\Request;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Ordem;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Classes\SolicitacaoVoucher\TipoUsuario;
use App\Models\Api\Trait\ValidarEmpresaDownloadTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\ModelWhereTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\ValidarRequestTrait;

final class DownloadModel extends ORM
{
    use PaginaTrait;
    use OrdemTrait;
    use ModelWhereTrait;
    use ValidarRequestTrait;
    use ValidarEmpresaDownloadTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_VOUCHER;

    private array $campoInicial;
    private int $idEmpresa;
    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa($request->usuario, 'empresa');
        $this->validarRequest();
        $this->validarCamposAceito();
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */
    public function download()
    {
        $campo = $this->converterCampoParaDownload();
        $query = $this
            ->campo($campo)
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem(new Ordem()));

        $campoUsuario = [];
        if (in_array('usuario_nome', $this->campoInicial)) {
            $campoUsuario[] = 'nome';
        }
        if (in_array('usuario_cpf', $this->campoInicial)) {
            $campoUsuario[] = 'documento';
        }
        if ($campoUsuario) {
            $query
                ->tabela(TABELA_USUARIO_CLIENTE)
                ->campo($campoUsuario, 'usuario')
                ->leftJoin('id', 'usuario');
        }

        $dado = $query->read();

        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado);
    }
    private function salvarLogDownload(array $dado)
    {
        $Log = new LogDownloadEntity(
            app: 'solicitacao_voucher',
            request: $this->request->dado(),
            quantidade: count($dado),
            usuario: $this->request->usuario
        );
        try {
            $Log->salvar();
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
        }
    }
    private function montarRetornoDownload(array $dado): array
    {
        $i = 0;
        $retorno = [];
        $Status = new Status();
        $Tipo = new Tipo();
        $TipoUsuario = new TipoUsuario();
        foreach ($dado as $linha) {
            foreach ($linha as $ind => $val) {
                if (in_array($ind, ['data_validacao', 'data_criacao', 'data_vencimento'])) {
                    $val = dataBr($val);
                } elseif ($ind == 'status') {
                    $val = $Status->indice($val);
                } elseif ($ind == 'tipo') {
                    $val = $Tipo->indice($val);
                } elseif ($ind == 'tipo_usuario') {
                    $val = $TipoUsuario->indice($val);
                } elseif ($ind == 'titulo') {
                    $ind = 'parceiro';
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
            'empresa', 'codigo', 'data_criacao', 'data_validacao', 'data_vencimento', 'status',
            'usuario_nome', 'usuario_cpf', 'parceiro', 'tipo_usuario', 'tipo'
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
        $this->campoInicial = jsonDecode($this->request->campo, true, true);
        $campo = array_flip($this->campoInicial);
        if (array_key_exists('empresa', $campo)) {
            unset($campo['empresa']);
        }
        if (array_key_exists('parceiro', $campo)) {
            unset($campo['parceiro']);
            $campo['titulo'] = true;
        }
        if (array_key_exists('usuario_cpf', $campo)) {
            unset($campo['usuario_cpf']);
        }
        if (array_key_exists('usuario_nome', $campo)) {
            unset($campo['usuario_nome']);
        }
        return array_keys($campo);
    }
}
