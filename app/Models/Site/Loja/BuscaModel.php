<?php

namespace App\Models\Site\Loja;

use App\Classes\ParceiroLoja\Estabelecimento;
use App\Classes\ParceiroLoja\Ordem;
use Helpers\ListaHelper;
use Http\Request;

final class BuscaModel
{
    private array $where = [];
    private array $filtro = [];
    private string $url = '';
    private Estabelecimento $Estabelecimento;
    private Ordem $Ordem;
    private array $estadoIndiceNome = [
        'acre'                => 'Acre',
        'alagoas'             => 'Alagoas',
        'amapa'               => 'Amapá',
        'amazonas'            => 'Amazonas',
        'bahia'               => 'Bahia',
        'ceara'               => 'Ceará',
        'distrito federal'    => 'Distrito Federal',
        'espirito santo'      => 'Espírito Santo',
        'goias'               => 'Goiás',
        'maranhao'            => 'Maranhão',
        'mato grosso'         => 'Mato Grosso',
        'mato grosso do sul'  => 'Mato Grosso do Sul',
        'minas gerais'        => 'Minas Gerais',
        'para'                => 'Pará',
        'paraiba'             => 'Paraíba',
        'parana'              => 'Paraná',
        'pernambuco'          => 'Pernambuco',
        'piaui'               => 'Piauí',
        'rio de janeiro'      => 'Rio de Janeiro',
        'rio grande do norte' => 'Rio Grande do Norte',
        'rio grande do sul'   => 'Rio Grande do Sul',
        'rondonia'            => 'Rondônia',
        'roraima'             => 'Roraima',
        'santa catarina'      => 'Santa Catarina',
        'sao paulo'           => 'São Paulo',
        'sergipe'             => 'Sergipe',
        'tocantins'           => 'Tocantins'
    ];
    private array $estadoIndiceUF = [
        'acre'                => 'AC',
        'alagoas'             => 'AL',
        'amapa'               => 'AP',
        'amazonas'            => 'AM',
        'bahia'               => 'BA',
        'ceara'               => 'CE',
        'distrito federal'    => 'DF',
        'espirito santo'      => 'ES',
        'goias'               => 'GO',
        'maranhao'            => 'MA',
        'mato grosso'         => 'MT',
        'mato grosso do sul'  => 'MS',
        'minas gerais'        => 'MG',
        'para'                => 'PA',
        'paraiba'             => 'PB',
        'parana'              => 'PR',
        'pernambuco'          => 'PE',
        'piaui'               => 'PI',
        'rio de janeiro'      => 'RJ',
        'rio grande do norte' => 'RN',
        'rio grande do sul'   => 'RS',
        'rondonia'            => 'RO',
        'roraima'             => 'RR',
        'santa catarina'      => 'SC',
        'sao paulo'           => 'SP',
        'sergipe'             => 'SE',
        'tocantins'           => 'TO'
    ];

    public function __construct(
        private readonly Request $request,
        private readonly ?string $busca = null
    ) {
        $this->url = route('loja.index');
        $this->Estabelecimento = new Estabelecimento($request->estabelecimento);
        $this->Ordem = new Ordem($request->ordem);

        if (!empty($busca)) {
            $this->converterPesquisaEmDado();
            return;
        }

        if ($this->requestVazio()) {
            return;
        }

        $this->converterDadoEmPesquisa();
    }

    /**
     */
    private function converterPesquisaEmDado(): void
    {
        $busca = str_replace('-', ' ', $this->busca);
        $busca = $this->montarWhereFiltro('pela ordem', $busca, 'ordem');
        $busca = $this->montarWhereFiltro('pela pesquisa', $busca, 'pesquisa');
        $busca = $this->montarWhereFiltro('em estabelecimento', $busca, 'estabelecimento');
        $busca = $this->montarWhereFiltro('pela tag', $busca, 'tag');
        $busca = $this->montarWhereFiltro('na categoria', $busca, 'categoria');
        $busca = $this->montarWhereFiltro('no estado', $busca, 'endereco_estado');
    }

    /**
     * @param $regex
     * @param $busca
     * @param $indice
     *
     * @return mixed
     */
    private function montarWhereFiltro($regex, $busca, $indice): mixed
    {
        if (!preg_match('/' . $regex . '/', $busca)) {
            return $busca;
        }

        $explode = explode($regex, $busca);
        $busca = trim(preg_replace('/\-$/', '', $explode[0]));
        $valor = trim($explode[1] ?? '');
        $valorReal = $valor;
        if ($indice === 'endereco_estado' && !array_key_exists($valor, $this->estadoIndiceNome)) {
            return $busca;
        } elseif ($indice === 'endereco_estado') {
            $valorReal = $this->estadoIndiceUF[$valor];
            $valor = $this->estadoIndiceNome[$valor];
        }

        $this->where[$indice] = $valorReal;
        if ($indice !== 'ordem') {
            $this->criarFiltro($indice, $valor, $valorReal);
        }
        return $busca;
    }

    /**
     * @param $indice
     * @param $valor
     * @param $valorReal
     *
     */
    private function criarFiltro($indice, $valor, $valorReal): void
    {
        $nome = [
            'endereco_estado' => 'Estado',
            'categoria'       => 'Categoria',
            'tag'             => 'Subcategoria',
            'pesquisa'        => 'Pesquisa',
            'estabelecimento' => 'Estabelecimento',
            'ordem'           => 'Ordem'
        ];
        if ($indice == 'estabelecimento') {
            $valor = (new Estabelecimento($valor))->nome();
        }
        $this->filtro[$indice] = object([
            'indice'     => $indice,
            'valor'      => $valor,
            'valor_real' => $valorReal,
            'nome'       => $nome[$indice]
        ]);
    }

    /**
     * @return bool
     */
    private function requestVazio(): bool
    {
        $request = $this->request;
        if (
            empty($request->categoria)
            && empty($request->tag)
            && empty($request->pesquisa)
            && !validarUf($request->estado)
            && !$this->Estabelecimento->valido()
            && !$this->Ordem->valido()
        ) {
            return true;
        }
        return false;
    }

    /**
     */
    private function converterDadoEmPesquisa(): void
    {
        $estado = (new ListaHelper())->estado()->r();

        $uri = '';
        foreach ($this->request->dado() as $ind => $val) {
            if (empty($val)) {
                continue;
            }

            if ($ind == 'estado' && validarUf($val)) {
                $uri .= '-no-estado-' . strSlug($estado[$val]);
            } elseif ($ind == 'categoria') {
                $uri .= '-na-categoria-' . strSlug($val);
            } elseif ($ind == 'tag') {
                $uri .= '-pela-tag-' . strSlug($val);
            } elseif ($ind == 'pesquisa') {
                $uri .= '-pela-pesquisa-' . urlencode($val);
            } elseif ($ind == 'estabelecimento' && $this->Estabelecimento->valido()) {
                $uri .= '-em-estabelecimento-' . strSlug($val);
            } elseif ($ind == 'ordem' && $this->Ordem->valido()) {
                $uri .= '-pela-ordem-' . strSlug($val);
            }
        }
        $this->url = route('loja.busca') . '/' . preg_replace('/^\-/', '', $uri);
    }

    /**
     * @return array
     */
    public function filtro(): array
    {
        return $this->filtro;
    }

    /**
     * @return array
     */
    public function where(): array
    {
        return $this->where;
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * @param string $indice
     *
     * @return string
     */
    public function valor(string $indice): string
    {
        $valor = $this->filtro[$indice]->valor_real ?? '';
        return ($indice === 'ordem') && empty($valor) ? 'favorito' : $valor;
    }
}
