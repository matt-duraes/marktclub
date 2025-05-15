<?php

namespace App\Models\Site\Saude;

use App\Classes\Comercial\Empresa\UUID;
use App\Classes\Saude\Cidade\Lista;
use App\Models\Site\ListarInterface;
use stdClass;

final class OperadoraModel implements ListarInterface
{
    /**
     * Lista os dados das operadoras de saúde com base no estado e cidade fornecidos
     *
     * @param string|null $estado Estado para filtrar as operadoras
     * @param string|null $cidade Cidade para filtrar as operadoras
     *
     * @return stdClass Retorna um objeto com o tipo 'operadora' e a lista de operadoras ativas
     */
    public function listarDados(?string $estado = null, ?string $cidade = null): stdClass
    {
        return (object)[
            'tipo'  => 'operadora',
            'lista' => $this->pegarListaAtiva($estado, $cidade),
        ];
    }

    /**
     * @return stdClass
     */
    public function listarDadosFederal(): stdClass
    {
        return (object)[
            'tipo'  => 'operadora',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Vitória',
                    'link'   => route('planosaude.unimedVitoria'),
                    'imagem' => LINK . '/images/site/logo_unimed_vitoria.jpg',
                    'tipo'   => 'operadora',
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Seguros',
                    'link'   => route('planosaude.unimedSeguro'),
                    'imagem' => LINK . '/images/site/saude-unimed-seguro.png',
                    'tipo'   => 'operadora',
                ],
            ],
        ];
    }

    /**
     * @return stdClass
     */
    public function saudeBoleto(): stdClass
    {
        return (object)[
            'tipo'  => 'operadora',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Vitória',
                    'link'   => 'https://www.benevix.com.br/boletos/',
                    'imagem' => LINK . '/images/site/logo_unimed_vitoria.jpg',
                    'tipo'   => 'operadora',
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Seguros',
                    'link'   => 'https://fenapef.admex.com.br/default.asp',
                    'imagem' => LINK . '/images/site/saude-unimed-seguro.png',
                    'tipo'   => 'operadora',
                ],
            ],
        ];
    }

    public function precisaEscolher(?string $estado): bool
    {
        return in_array(CLUBE_EMPRESA, [UUID::CFM, UUID::YOUHUUL]) && empty($estado);
    }

    private function pegarListaAtiva(?string $estado, ?string $cidade): array
    {
        $lista = [
            (object)[
                'id'       => uuid(),
                'titulo'   => 'Unimed Vitória',
                'link'     => route('planosaude.unimedVitoria'),
                'imagem'   => LINK . '/images/site/logo_unimed_vitoria.jpg',
                'tipo'     => 'operadora',
                'escolher' => false,
                'status'   => MENU_SAUDE_VITORIA,
            ],
            (object)[
                'id'       => uuid(),
                'titulo'   => 'Amil',
                'link'     => route('planosaude.amil'),
                'imagem'   => LINK . '/images/site/logo_amil.png',
                'tipo'     => 'operadora',
                'escolher' => false,
                'status'   => MENU_SAUDE_AMIL,
            ],
            (object)[
                'id'       => uuid(),
                'titulo'   => 'Unimed - Florianópolis',
                'link'     => route('planosaude.unimedflorianopolis'),
                'imagem'   => LINK . '/images/site/logo_unimed_florianopolis.jpg',
                'tipo'     => 'operadora',
                'escolher' => false,
                'status'   => MENU_SAUDE_FLORIANOPOLIS,
            ],
            (object)[
                'id'       => uuid(),
                'titulo'   => 'Unimed Seguros',
                'link'     => route('planosaude.unimedSeguro'),
                'imagem'   => LINK . '/images/site/saude-unimed-seguro.png',
                'tipo'     => 'operadora',
                'escolher' => false,
                'status'   => MENU_SAUDE_SEGURO,
            ],
            (object)[
                'id'       => uuid(),
                'titulo'   => 'Unimed Natal',
                'link'     => route('planosaude.unimedNatal'),
                'imagem'   => LINK . '/images/site/logo_unimed_natal.png',
                'tipo'     => 'operadora',
                'escolher' => true,
                'status'   => in_array(CLUBE_EMPRESA, [UUID::CFM, UUID::YOUHUUL]) && $estado === 'RN',
            ],
            (object)[
                'id'       => uuid(),
                'titulo'   => 'Unimed Jundiaí',
                'link'     => route('planosaude.unimedJundiai'),
                'imagem'   => LINK . '/images/site/logo_unimed_jundiai.png',
                'tipo'     => 'operadora',
                'escolher' => true,
                'status'   => in_array(CLUBE_EMPRESA, [UUID::CFM, UUID::YOUHUUL]) && $this->validarUnimedJundiai(
                        $estado,
                        $cidade
                    ),
            ],
        ];

        $retorno = [];
        foreach ($lista as $r) {
            if ($r->status != 1) {
                continue;
            }
            $retorno[] = $r;
        }
        return $retorno;
    }

    private function validarUnimedJundiai(?string $estado, ?string $cidade): bool
    {
        $Lista = new Lista();
        $listaEstado = $Lista->pegarEstado();
        $listaCidade = $Lista->pegarCidade($estado);
        return array_key_exists($estado, $listaEstado) && in_array($cidade, $listaCidade);
    }
}

